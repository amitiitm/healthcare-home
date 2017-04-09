<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayumoneyTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::create('payumoney_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('transaction_date');
            $table->string('invoice_number',50);
            $table->string('status',50);
            $table->string('message',500);
            $table->decimal('amount');
            $table->string('customer_name',250);
            $table->string('customer_email',250);
            $table->string('payment_url',500);
            $table->string('payment_id',200);
            $table->string('email_sent',50);
            $table->string('error_code',50)->nullable();
            $table->integer('response_code')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('payumoney_transactions');
    }
}
