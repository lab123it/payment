<?php

namespace Lab123\Payment\Repositories;

use Lab123\Payment\Entities\TransactionStatus;
use Lab123\Odin\Repositories\Repository;

class TransactionStatusRepository extends Repository
{

    protected $tree_uri = [];

    public function __construct()
    {
        $this->model = new TransactionStatus();
    }
}