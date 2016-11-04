<?php

namespace Lab123\Payment\Providers;

use Illuminate\Support\ServiceProvider;
use Lab123\Payment\Entities\Transaction;
use Lab123\Payment\Observers\TransactionObserver;

class PaymentServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../Config/payment.php' => config_path('payment.php'),
            __DIR__ . '/../Database/Migrations/' => base_path('database/migrations')
        ]);
        
        $this->binds();
        $this->facades();
        $this->register();
    }

    public function register()
    {
    	Transaction::observe(TransactionObserver::class);
    }

    private function binds()
    {
        $provider = config('payment.provider');
        
        $this->app->bind(
        	'Packages\\Payment\\Contracts\\IWebhook', 
        	"Packages\\Payment\\Services\\{$provider}\\Webhook"
        );
        
        $this->app->bind(
        	'Packages\\Payment\\Contracts\\ITransaction', 
        	"Packages\\Payment\\Services\\{$provider}\\Transaction"
        );
    }

    private function facades()
    {
        $this->app->booted(function () {
            $loader = \Illuminate\Foundation\AliasLoader::getInstance();
            $loader->alias('PaymentWebhookLog', 'Packages\\Payment\\Logger\\PaymentWebhookLog');
        });
    }
}