<?php

namespace Lab123\Payment\Services\PagarMe;

use App;
use Log;
use PagarMe;
use Illuminate\Http\Request;
use Lab123\Payment\Services\Config;
use Lab123\Payment\Contracts\IWebhook;
use Lab123\Payment\Facades\PaymentWebhookLog;
use Lab123\Payment\Repositories\TransactionRepository;
use Lab123\Payment\Repositories\StatusTransactionRepository;

class Webhook implements IWebhook
{

    private $transaction;

    public function __construct(Request $request, TransactionRepository $repository)
    {
        $this->data = (object) $request->all();
        $this->repository = $repository;
    }

    public function receiver()
    {
        Pagarme::setApiKey(Config::getKey());
        
        PaymentWebhookLog::info("Webhook Receiver - " . json_encode($this->data));
        
        if (! $this->validate()) {
            Log::error('Webhook Inválido!');
            PaymentWebhookLog::info('Webhook Inválido!');
            
            die('Webhook Inválido!');
        }
        
        $this->getTransaction()->updateTransaction();
    }

    private function getTransaction()
    {
        $this->transaction = $this->repository->findBy([
            'transaction_id',
            '=',
            $this->data->id
        ])[0];
        
        PaymentWebhookLog::info("Get Transaction  - " . $this->data->id);
        PaymentWebhookLog::info($this->transaction);
        
        return $this;
    }

    private function updateTransaction()
    {
        $status_id = $this->getIdStatus($this->data->current_status);
        // Log::info($status_id);
        PaymentWebhookLog::info('Status transaction: ' . $this->data->current_status);
        PaymentWebhookLog::info('New Status transaction: ' . $status_id);
        
        $this->transaction->status_id = $status_id;
        
        return $this->transaction->save();
    }

    private function getIdStatus($gateway_status)
    {
        $statusRepository = new StatusTransactionRepository();
        
        $status = $statusRepository->findBy([
            [
                'gateway_status',
                '=',
                $gateway_status
            ],
            [
                'gateway',
                '=',
                config('payment.gateway')
            ]
        ], null, null, null, null, 'id')[0];
        
        return $status->id;
    }

    public function validate()
    {
        if (App::environment('local')) {
            return true;
        }
        
        /*if (! PagarMe::validateFingerprint($this->data->id, $this->data->fingerprint)) {
            Log::error('POSTBack Payment Pagar.me - Fingerprint inválido.');
            PaymentWebhookLog::info('Fingerprint inválido. MAS RETORNANDO TRUE PARA VALIDAR ESSE IF');
            return true;
        }*/
        
        return true;
    }
}