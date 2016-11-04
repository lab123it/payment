<?php

namespace Lab123\Payment\Contracts;

use Illuminate\Http\Request;
use Lab123\Payment\Repositories\TransactionRepository;

interface IWebhook
{
    public function __construct(Request $request, TransactionRepository $repository);

    public function validate();

    public function receiver();
}