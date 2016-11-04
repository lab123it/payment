<?php

namespace Lab123\Payment\Services\Iugu;

use \PagarMe;
use \Exception;
use \PagarMe_Transaction;
use Tymon\JWTAuth\Facades\JWTAuth;
use Lab123\Payment\Services\Config;
use Lab123\Payment\Contracts\ITransaction;
use Lab123\Payment\Facades\PaymentWebhookLog;
use Lab123\Payment\Repositories\TransactionRepository;
use Lab123\Payment\Repositories\StatusTransactionRepository;
use Lab123\Payment\Logger\Lab123\Payment\Logger;
use Log;
use DB;

class Transaction implements ITransaction
{

    private $pagarmeTransaction;

    public function __construct()
    {
        \Iugu::setApiKey(Config::getKey());
        $this->transaction = new TransactionRepository();
    }

    public function capture(array $data)
    {
        $token = $data['token'];
        $cost = $data['cost'];
        $order_id = $data['order_id'];
        
        PaymentWebhookLog::info($data);
        
        try {
            
            $this->pagarmeTransaction = PagarMe_Transaction::findById($token);
            
            $this->pagarmeTransaction->capture((int) ($cost * 100));
            
            $this->save($token, $cost, $order_id);
            PaymentWebhookLog::info("Transaction salva");
            
            return  $this->pagarmeTransaction;
        } catch (Exception $e) {
            
            if ($this->pagarmeTransaction != null) {
                Log::error("PagarMe - Erro ao realizar estorno. PagarMe_id: {$this->pagarmeTransaction->id}");
                PaymentWebhookLog::error("PagarMe - Erro ao realizar estorno. PagarMe_id: {$this->pagarmeTransaction->id}");
                
                // Estorna a transação
                if ($this->pagarmeTransaction->payment_method === 'credit_card') {
                    $this->pagarmeTransaction->refund();
                }
            }
            
            throw $e;
        }
    }

    private function save($token, $price, $order_id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        
        $statusTransactionRepository = new StatusTransactionRepository();
        
        $status = ($statusTransactionRepository->findBy([
            [
                'gateway_status',
                '=',
                $this->pagarmeTransaction->status
            ],
            [
                'gateway',
                '=',
                config('payment.gateway')
            ]
        ]));
        
        $status = $status->first();
        
        $data['user_id'] = $user->id;
        $data['transaction_id'] = $this->pagarmeTransaction->id;
        $data['order_id'] = $order_id;
        $data['status_id'] = $status->id;
        $data['amount'] = $price;
        $data['token'] = $token;
        $data['url_boleto'] = $this->pagarmeTransaction->boleto_url;
        $data['gateway'] = config('payment.gateway');
        
        $transaction = $this->transaction->create($data);
        
        return $transaction;
    }

    private function getValue($order_id)
    {
        $order = \Packages\order\Entities\order::find($order_id);
        
        $transformer = new \Packages\order\Transformers\orderTransformer();
        
        $order = $transformer->setModel($order)->convertToClient();
        
        return $order['credits'];
    }
}