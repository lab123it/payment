<?php

namespace Lab123\Payment\Services;

use Lab123\Payment\Contracts\ICustomer;

class Customer implements ICustomer
{
	/**
	 * @var string
	 */
	protected $provider;
	
	/**
	 * @var ICustomer
	 */
	protected $customer;
	
	/**
	 * @param string $provider
	 */
	public function __construct(string $provider = null)
	{
		$this->provider = $provider ?: config('payment.provider');
		$classname = "Lab123\Payment\Services\{$this->provider}\Customer";
		$this->customer = new $classname();
	}
	
	public function find(string $id) 
	{
		return $this->customer->find($id);
	}
	
	public function create(array $data)
	{
		return $this->customer->create($data);
	}
	
	public function update(string $id, array $data)
	{
		return $this->customer->update($id, $data);
	}
}