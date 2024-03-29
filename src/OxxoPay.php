<?php

namespace ApsCreativas\OxxoPay;

use Conekta\Conekta;

class OxxoPay
{
    public function __construct()
    {
        Conekta::setApiKey(config('oxxopay.api_key'));
        Conekta::setApiVersion(config('oxxopay.api_version'));
        Conekta::setLocale(config('app.locale'));
    }
}
