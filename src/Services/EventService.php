<?php

namespace Lab123\Payment\Services;

use Lab123\Payment\Repositories\TransactionStatusRepository;

class EventService
{

    public function TransactionUpdate($transaction)
    {
        $repository = new TransactionStatusRepository();
        
        $status = $repository->find($transaction->status_id);
        
        $eventPaid = config("payment.events.status_change.{$status->provider_name}");
        
        if (!$eventPaid) {
            return;
        }
        
        \Event::fire(new $eventPaid($transaction));
    }
}