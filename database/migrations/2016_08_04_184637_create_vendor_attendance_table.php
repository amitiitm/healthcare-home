<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorAttendanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_vendor_attendance', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lead_id');
            $table->integer('vendor_id')->nullable();
            $table->date('date');
            //$table->integer('vendor_id');
            $table->boolean('is_present');
            $table->float('vendor_price')->nullable();
            $table->string('comment')->default('');
            $table->integer('marked_by')->nullable();
            $table->string('medium',30)->nullable();
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
        Schema::drop('lead_vendor_attendance');
    }
}
