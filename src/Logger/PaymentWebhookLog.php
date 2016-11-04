<?php

namespace Lab123\Payment\Logger;

/**
 * PaymentWebhookLog
 *
 * Custom monolog logger for CMS user activity logging
 */
class PaymentWebhookLog extends Logger
{

    protected $log_name = 'PaymentWebhookLog';

    protected $file_name = 'payment-webhook';

    protected $formater = "[%datetime%] %channel%.%level_name%: %message% \n";

    protected function canLog()
    {
        if (! config('payment.log.webhook')) {
            return false;
        }
        
        return true;
    }
}