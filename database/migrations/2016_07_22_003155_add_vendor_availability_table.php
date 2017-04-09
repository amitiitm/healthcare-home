<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVendorAvailabilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_availabilities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vendor_id');
            $table->integer('available');
            $table->integer('available_date')->nullable();
            $table->integer('changed_shift_id')->nullable();
            $table->integer('changed_zone_id')->nullable();
            $table->integer('reason_id')->nullable();
            $table->string('other_reason')->default("");
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
        Schema::drop('vendor_availabilities');
    }
}
