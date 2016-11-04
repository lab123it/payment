<?php

namespace Lab123\Payment\Contracts;

interface ITransaction
{
    public function capture(array $data);
}