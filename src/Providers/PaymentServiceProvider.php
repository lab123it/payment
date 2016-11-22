<?php

namespace Lab123\Payment\Providers;

use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../Config/payment.php' => config_path('payment.php')
        ]);
        
        $this->binds();
        $this->facades();
    }

    private function binds()
    {
        $provider = config('payment.provider');
        
        $this->app->bind(
        	'Lab123\\Payment\\Contracts\\IWebhook', 
        	"Lab123\\Payment\\Services\\{$provider}\\Webhook"
        );
        
        $this->app->bind(
        	'Lab123\\Payment\\Contracts\\ITransaction', 
        	"Lab123\\Payment\\Services\\{$provider}\\Transaction"
        );
    }

    private function facades()
    {
        class_alias('Lab123\Payment\Logger\PaymentWebhookLog', 'PaymentWebhookLog');
    }
}