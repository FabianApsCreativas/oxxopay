<?php

use Conekta\Conekta;

trait OxxoPay {

    public static function bootOxxoPay()
    {
        Conekta::setApiKey(config('oxxopay.api_key'));
        Conekta::setApiVersion(config('oxxopay.api_version'));
        Conekta::setLocale('es');
    }
}