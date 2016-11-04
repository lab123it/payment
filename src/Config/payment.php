<?php

return [
		
    'connection' => 'default',
		
    'provider' => 'Iugu',
    
    'key' => env('PAYMENT_KEY', ''),
		
    'encryptKey' => env('PAYMENT_ENC_KEY', ''),
    
    'events' => [
        'status_change' => [
            'paid' => \Gandalf\Events\PaymentPaid::class
        ]
    ],
    
    'log' => [
        'webhook' => env('PAYMENT_LOG_WEBHOOK', true)
    ]
];