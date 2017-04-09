<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaregiverAutoAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('caregiver_auto_attendances', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('lead_id');
            $table->string('response_id',50)->nullable();
            $table->string('caregiver_name')->nullable();
            $table->string('mobile',50);
            $table->string('reason_phrase',50)->nullable();
            $table->string('status_code',50)->nullable();
            $table->text('response')->nullable();
            $table->string('dtmf_input',50)->nullable();
            $table->string('time_slot',50)->nullable();
            $table->timestamps();
            $table->index('created_at');
            $table->index('mobile');
            $table->index('caregiver_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('caregiver_auto_attendances');
    }
}
