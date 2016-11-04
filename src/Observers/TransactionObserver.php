<?php

namespace Lab123\Payment\Observers;

use Lab123\Odin\Observers\Observer;
use Lab123\Payment\Services\EventService;

class TransactionObserver extends Observer
{
	/**
	 * Active observer in trigger updated
	 *
	 * @param Entity $entity
	 */
	public function updated($transaction) 
	{
		$dirt = $transaction->getDirty();
		
		/* Verifica se o status foi alterado no update */
		if (!key_exists('status_id', $dirt)) {
			return;
		}
		
		$eventService = new EventService();
		$eventService->TransactionUpdate($transaction);
	}
}