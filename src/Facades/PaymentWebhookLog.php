<?php

namespace Lab123\Payment\Facades;

use Illuminate\Support\Facades\Facade;

class PaymentWebhookLog extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'PaymentWebhookLog';
    }
}