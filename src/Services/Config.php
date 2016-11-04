<?php

namespace Lab123\Payment\Services;

class Config
{

    public static function getKey()
    {
        return config('payment.key');
    }

    public static function getKeyEncrypted()
    {
        return config('payment.encryptKey');
    }
}