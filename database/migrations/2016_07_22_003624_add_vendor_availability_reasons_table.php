<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVendorAvailabilityReasonsTable extends Migration
{

    public function up()
    {
        Schema::create('vendor_availability_reasons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug');
            $table->string('label');
            $table->integer('availability_option_id');
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
        Schema::drop('vendor_availability_reasons');
    }
}
