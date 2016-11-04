<?php

namespace Lab123\Payment\Entities;

use Lab123\Odin\Entities\Entity;

class TransactionStatus extends Entity
{

    /**
     * Tabela usada pelo model
     *
     * @var string
     */
    protected $table = 'transaction_status';

    /**
     * Campos que podem ser preenchidos por mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'provider_name',
    	'provider'
    ];

    /**
     * Define o relacionamento com a conta corrente
     *
     * @return Elouquent
     */
    public function transaction()
    {
        return $this->hasMany(\Lab123\Payment\Entities\Transaction::class);
    }
}