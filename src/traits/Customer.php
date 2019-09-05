<?php

namespace ApsCreativas\OxxoPay\Traits;

use Carbon\Carbon;
use Conekta\Customer as ConektaCustomer;
use Conekta\Handler;
use Exception;
use OxxoPay;

trait Customer
{
    use OxxoPay;
    
    public function getCustomer()
    {
        return ConektaCustomer::find($this->oxxopay_customer_id);
    }

    public function createCustomer()
    {
        if ($this->isCustomer()) {
            return $this;
        }
        try {
            $customer = ConektaCustomer::create([
                'name' => $this->name,
                'email' => $this->email,
            ]);
            $this->update([
                'oxxopay_customer_id' => $customer->id
            ]);
            return $this;
        } catch (Handler $error) {
            return $error->getMessage();
        }
    }

    public function updateCustomer()
    {
        if (!$this->isCustomer()) {
            throw new Exception();
        }
        $customer = ConektaCustomer::find($this->oxxopay_customer_id);
        $customer->update([
            'name' => $this->name,
            'email' => $this->email
        ]);
    }

    public function deleteCustomer()
    {
        try {
            $customer = ConektaCustomer::find($this->oxxopay_customer_id);
            $customer->delete();
            $this->update([
                'oxxopay_customer_id' => null
            ]);
            return $this;
        } catch (Handler $error) {
            return $error->getMessage();
        }
    }

    public function isCustomer()
    {
        return !!$this->oxxopay_customer_id;
    }

    public function createPaymentSource($token)
    {
        try {
            $customer = ConektaCustomer::find($this->oxxopay_customer_id);
            return $customer->createPaymentSource([
                'token_id' => $token,
                'type' => 'card'
            ]);
            
            return $this;
        } catch (Handler $error) {
            return $error->getMessage();
        }
    }

    public function hasPaymentMethod()
    {
        if (!$this->isCustomer()) {
            return false;
        }

        $customer = ConektaCustomer::find($this->oxxopay_customer_id);

        return $customer->payment_sources->total > 0;
    }

    public function paymentMethods()
    {
        if (!$this->isCustomer()) {
            return [];
        }

        $customer = ConektaCustomer::find($this->oxxopay_customer_id);

        return $customer->payment_sources;
    }

}
