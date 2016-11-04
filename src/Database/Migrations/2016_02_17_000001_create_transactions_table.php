<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            
            $table->integer('customer_id')->unsigned();
            $table->integer('provider_id')->unsigned();
            $table->integer('product_id')->unsigned()->index();
            $table->integer('status_id')->unsigned();
            
            $table->decimal('amount');
            $table->decimal('discount');
            $table->string('token', 255)->nullable();
            $table->string('provider', 20);
            $table->string('url_boleto')->nullable();

            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('status_id')->references('id')->on('transaction_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('transactions');
    }
}