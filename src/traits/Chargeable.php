<?php

namespace ApsCreativas\OxxoPay\Traits;

use Conekta\Handler;
use Conekta\Order;
use Conekta\ParameterValidationError;
use Exception;
use Illuminate\Support\Facades\Validator;
use OxxoPay;

trait Chargeable
{
    use OxxoPay;

    private $shipping_contact;
    private $items;
    private $conekta_order;

    public function createOrder()
    {
        $order = [
            'line_items' => $this->items,
            'currency' => config('oxxopay.currency'),
            'customer_info' => [
                'customer_id' => $this->client->oxxopay_customer_id
            ],
        ];

        if ($this->shipping_contact) {
            $order['shipping_contact'] = [
                'address' => $this->shipping_contact
            ];
        }

        try {
            $this->conekta_order = Order::create($order);
            $this->update([
                'oxxopay_order_status' => $this->conekta_order['payment_status'],
                'oxxopay_order_id' => $this->conekta_order['id'],
                'oxxopay_order' => $this->conekta_order
            ]);
            return $this;
        } catch (ParameterValidationError  $error) {
            throw new ParameterValidationError;
        } catch (Handler $error) {
            throw new Handler($error);
        }
    }

    public function oxxopay()
    {
        $this->conekta_order = Order::find($this->oxxopay_order_id);
        $this->conekta_charge = $this->conekta_order->createCharge([
            'payment_method' => [
                'type' => 'oxxo_cash'
            ]
        ]);
        return $this->conekta_charge;
    }

    public function charge($token = null)
    {
        try {
            $this->conekta_order = Order::find($this->oxxopay_order_id);
            $this->conekta_charge = $this->conekta_order->createCharge([
                'payment_method' => [
                    'type' => ($token) ? $token : 'default',
                    'token_id' => $token
                ]
            ]);

            return $this;
        } catch (ParameterValidationError $error) {
            throw new Exception($error->getMessage());
        } catch (Handler $error) {
            throw new Exception($error->getMessage());
        }
    }

    public function setShippingContact($shipping_contact)
    {
        $validator = Validator::make($shipping_contact, [
            'street1' => 'required|string',
            'postal_code' => 'required|numeric',
            'country' => 'required'
        ]);

        if ($validator->fails()) {
            throw new Exception($validator->getMessageBag());
        }
        $this->shipping_contact = $shipping_contact;
        return $this;
    }

    public function setItems($items)
    {
        $validator = Validator::make($items, [
            '*.name' => 'required|string',
            '*.unit_price' => 'required|integer',
            '*.quantity' => 'required|integer'
        ]);

        if ($validator->fails()) {
            throw new Exception($validator->getMessageBag());
        }

        $this->items = $items;
        return $this;
    }

    public function order()
    {
        return Order::find($this->oxxopay_order_id);
    }

    public function isPaid()
    {
        return $this->oxxopay_order_status === 'paid';
    }
}
