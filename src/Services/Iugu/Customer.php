<?php

namespace Lab123\Payment\Services\Iugu;

use Lab123\Payment\Contracts\ICustomer;

class Customer implements ICustomer
{
	/**
	 * @var array
	 */
	protected $fillable = [
		'email',
		'name',
		'cpf_cnpj'
	];
	
	public function __construct()
	{
		\Iugu::setApiKey(config('payment.keys.iugu.api_key'));
	}
	
	/**
	 * @param string $id
	 * 
	 * @return \Iugu_Customer
	 */
	public function find(string $id)
	{
		return \Iugu_Customer::fetch($id);
	}
	
	/**
	 * @param array $data
	 * 
	 * @return \Iugu_Customer
	 */
	public function create(array $data)
	{
		
		$customer = \Iugu_Customer::create(array_only($data, $this->fillable));
		
		if (array_key_exists('payment_method', $array)) {
			$this->addPaymentMethod($customer, $array['payment_method']);
		}
		
		return $customer;
	}
	
	/**
	 * @param string $id
	 * @param array $data
	 * 
	 * @return \Iugu_Customer
	 */
	public function update(string $id, array $data) 
	{
		$customer = $this->find($id);
		
		foreach ($fillable as $key) {
			if (array_key_exists($key, $data)) {
				$customer->$key = $data[$key];
			}
		}
		
		$customer->save();
		
		if (array_key_exists('payment_method', $array)) {
			$this->addPaymentMethod($customer, $array['payment_method']);
		}
		
		return $customer;
	}
	
	/**
	 * @param \Iugu_Customer $customer
	 * @param array $data
	 * 
	 * @return \Iugu_PaymentMethod
	 */
	private function addPaymentMethod(\Iugu_Customer $customer, array $data)
	{
		return $customer->payment_methods()->create(Array(
			'description' => $data['description'],
			'token' => $data['token'],
			'set_as_default' => true
		));
	}
}