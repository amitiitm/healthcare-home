<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadCustomerVendorStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_vendor_statuses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lead_id');
            $table->integer('customer_id');
            $table->string('vendor_status_key');
            $table->string('comment')->default();
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
        Schema::drop('customer_vendor_statuses');
    }
}
