<?php

namespace Lab123\Payment\Contracts;

interface ICustomer
{
	public function find(string $id);
	
	public function create(array $data);
	
	public function update(string $id, array $data);
}