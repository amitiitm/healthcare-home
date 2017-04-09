<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('lead_id');
            $table->decimal('price_per_day')->nullable();
            $table->decimal('total_amount')->nullable();
            $table->decimal('remaining_amount')->nullable();
            $table->integer('discount_type')->nullable();
            $table->decimal('discount_amount')->nullable();
            $table->decimal('tax_per')->nullable();
            $table->decimal('tax_amount')->nullable();
            $table->integer('payment_type_id')->nullable();
            $table->integer('payment_mode_id')->nullable();
            $table->integer('payment_period_id')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('locality_id')->nullable();
            $table->integer('city_id')->nullable();
            $table->integer('service_id');
            $table->string('customer_name');
            $table->integer('invoice_type');
            $table->string('email',255);
            $table->string('address',500)->nullable();
            $table->string('landmark',500)->nullable();
            $table->string('phone',15)->nullable();
	    $table->string('payumoney_url',500)->nullable();
            $table->integer('price_unit_id')->nullable();
            $table->integer('assigned_user_id')->nullable();
            $table->text('assigned_user_comments')->nullable();
            $table->string('status',50)->nullable();
            $table->string('invoice_number',255);
            $table->date('service_start_date');
            $table->date('invoice_from_date');
            $table->date('invoice_to_date');
            $table->integer('billing_days');
            $table->integer('pending_days')->nullable();
            $table->decimal('outstanding_amount')->nullable();
            $table->dateTime('date_of_collection');
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
        Schema::drop('invoices');
    }
}
