<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicePaymentCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_payment_collections', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('invoice_id');
            $table->integer('payment_mode_id')->nullable();
            $table->string('payment_status',50)->nullable();
            $table->string('unpaid_payment_reason',50)->nullable();
            $table->decimal('paid_amt')->default(0.00);
            $table->decimal('outstanding_amt')->default(0.00);
            $table->string('bank_name',100)->nullable();
            $table->string('cheque_no',50)->nullable();
            $table->string('outstanding_amt_type',50)->nullable();
            $table->string('recoverable_outstanding_amt_status',50)->nullable();
            $table->string('nonrecoverable_outstanding_amt_status',50)->nullable();
            $table->dateTime('expected_payment_date')->nullable();
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
        Schema::drop('invoice_payment_collections');
    }
}
