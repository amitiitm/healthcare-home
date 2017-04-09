<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhysiotherapyLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('physiotherapy_leads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lead_id');
            $table->string('condition')->nullable();
            $table->string('present_condition')->nullable();
            $table->string('chief_complaint')->nullable();
            $table->string('modalities')->nullable();
            $table->dateTime('expected_closing_date')->nullable();
            $table->integer('no_of_sessions')->nullable();
            $table->string('pricing')->nullable();
            $table->string('condition_id')->nullable();
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
        Schema::drop('physiotherapy_leads');
    }
}
