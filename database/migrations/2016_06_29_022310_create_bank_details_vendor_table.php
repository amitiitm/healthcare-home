<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBankDetailsVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_bank_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vendor_id');
            $table->string('name')->default('');;
            $table->string('bank_name')->default('');;
            $table->string('account_no')->default('');;
            $table->string('ifsc')->default('');
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
        Schema::drop('vendor_bank_details');
    }
}
