<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerVendorAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_vendor_attendances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lead_id');
            $table->integer('customer_id');
            $table->boolean('is_present')->default(false);
            $table->boolean('is_on_time')->default(false);
            $table->boolean('is_well_dressed')->default(false);
            $table->date('attendance_date')->nullable();
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
        Schema::drop('customer_vendor_attendances');
    }
}
