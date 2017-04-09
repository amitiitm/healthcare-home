<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientPhysiotherapyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patient_physiotherapy', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id');
            $table->integer('condition_id')->nullable();
            $table->string('pain_severity')->nullable();
            $table->string('mobility_id')->nullable();
            $table->string('motion_range')->nullable();
            $table->text('present_condition')->default('');
            $table->text('modality_id')->nullable();
            $table->text('no_of_sessions')->nullable();
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
        Schema::drop('patient_physiotherapy');
    }
}
