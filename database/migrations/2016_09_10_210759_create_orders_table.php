<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('lead_id');
            $table->integer('order_status');
            $table->integer('order_type')->nullable();
            $table->string('order_number',50);
            $table->dateTime('order_date');
            $table->decimal('total_amount');
            $table->decimal('remaining_amount');
            $table->integer('discount_type');
            $table->decimal('discount_amount');
            $table->decimal('tax_per');
            $table->decimal('tax_amount');
            $table->integer('payment_type_id');
            $table->integer('payment_mode_id');
            $table->integer('payment_period_id');
            $table->integer('service_id');
            $table->decimal('price');
            $table->integer('price_unit_id');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->softDeletes();
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
        Schema::drop('orders');
    }
}
