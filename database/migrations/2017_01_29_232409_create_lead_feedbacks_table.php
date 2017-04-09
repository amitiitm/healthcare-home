<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadFeedbacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_feedbacks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lead_id')->nullable();
            $table->string('lead_name')->nullable();
            $table->string('lead_mobile')->nullable();
            $table->integer('patient_id')->nullable();
            $table->string('patient_name')->nullable();
            $table->integer('caregiver_id')->nullable();
            $table->string('caregiver_name')->nullable();
            $table->integer('employee_id')->nullable();
            $table->string('employee_name')->nullable();
            $table->integer('created_by')->nullable();
            $table->string('created_by_name')->nullable();
            $table->dateTime('feedback_date');
            $table->string('status')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
            $table->index('lead_name');
            $table->index('lead_mobile');
            $table->index('patient_name');
            $table->index('caregiver_name');
            $table->index('created_by_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lead_feedbacks');
    }
}
