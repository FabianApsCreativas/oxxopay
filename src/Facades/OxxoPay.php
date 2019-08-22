<?php

namespace ApsCreativas\OxxoPay\Facades;

use Illuminate\Support\Facades\Facade;

class OxxoPay extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'oxxopay';
    }
}