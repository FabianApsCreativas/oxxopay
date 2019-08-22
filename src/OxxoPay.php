<?php

namespace ApsCreativas\OxxoPay;

use Conekta\Conekta;
use Conekta\Handler;
use Conekta\Order;
use Conekta\ParameterValidationError;
use Illuminate\Support\Facades\Log;

class OxxoPay
{
    private $currency;
    private $items;
    private $shipping_address;
    private $customer_info;

    public function __construct()
    {
        Conekta::setApiKey(config('oxxopay.api_key'));
        Conekta::setApiVersion(config('oxxopay.api_version'));
        Conekta::setLocale(config('app.locale'));
        $this->currency = config('oxxopay.currency');
    }

    /**
     * @param string $currency String currency code in ISO 4217
     * 
     * @return $this
     */
    public function setCurrency(string $currency)
    { 
        $this->$currency = $currency;
        return $this;
    }

    /**
     * @param array $items An array of items with keys name, unit_price, quantity
     * 
     * @return $this
     */
    public function setItems($items)
    {
        $this->items = $items;
        return $this;
    }

    /**
     * @param array $shipping An array with keys 
     *
     * @return $this
     */
    public function setShippingAddress($shipping_address)
    {
        $this->shipping_address = $shipping_address;
        return $this;
    }

    /**
     * @param $customer
     */
    public function setCustomerInfo($customer_info)
    {
        $this->customer_info = $customer_info;
        return $this;
    }

    /**
     * @return Conekta\Order
     */
    public function order()
    {
        try {
            $order = Order::create([
                'line_items' => $this->items,
                'currency' => $this->currency,
                'customer_info' => $this->customer_info,
                'shipping_contact' => [
                    'address' => $this->shipping_address
                ],
                'charges' => [[
                    'payment_method' => [
                        'type' => 'oxxo_cash'
                    ]
                ]]
            ]);
            return $order;
        } catch (ParameterValidationError  $error) {
            Log::debug($error);
            throw new ParameterValidationError;
        } catch (Handler $error) {
            Log::debug($error);
            throw new Handler($error);
        }
    }
}
