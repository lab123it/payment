<?php

namespace Lab123\Payment\Repositories;

use Lab123\Payment\Entities\Transaction;
use FMB\Repositories\Site\Repository;

class TransactionRepository extends Repository
{

    protected $tree_uri = [];

    public function __construct()
    {
        $this->model = new Transaction();
    }
    
    public function getByTransactionId($transactionId)
    {
        return $this->model->where('transaction_id', $transactionId)->first();
    }
}