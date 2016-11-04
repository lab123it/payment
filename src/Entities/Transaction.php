<?php

namespace Lab123\Payment\Entities;

use Lab123\Odin\Entities\Entity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Entity
{
    use SoftDeletes;

    protected $table = 'transactions';

    protected $with = [
        'status'
    ];

    protected $fillable = [
        'customer_id',
        'provider_id',
        'product_id',
        'status_id',
        'amount',
        'discount',
        'token',
        'provider',
        'url_boleto'
    ];

    protected $appends = [
        'payment_method'
    ];

    /**
     * @return string
     */
    public function getPaymentMethodAttribute()
    {
        return ($this->url_boleto) ? 'Boleto' : 'Cartão de Crédito';
    }

    public function status()
    {
        return $this->belongsTo(\Lab123\Payment\Entities\TransactionStatus::class, 'status_id');
    }

    public function product()
    {
        return $this->belongsTo(config('payment.product_class'), 'product_id');
    }

    public function customer()
    {
        return $this->belongsTo(config('payment.user_class'), 'customer_id');
    }
}