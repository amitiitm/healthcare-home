<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadVendorTrainingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_vendor_trainings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lead_vendor_id');
            $table->integer('task_id');
            $table->boolean('training')->default(false);
            $table->integer('marked_by_user_id')->nullable();
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
        Schema::drop('lead_vendor_trainings');
    }
}
