<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('gender')->nullable();
            $table->integer('age')->nullable();
            $table->integer('weight')->nullable();
            $table->boolean('is_on_equipment')->default(false);
            $table->integer('equipment_id')->nullable();
            $table->integer('shift_id')->nullable();
            $table->string('mobility')->nullable();
            $table->string('illness')->nullable();
            $table->string('physical_condition')->nullable();
            $table->time('morning_wakeup_time')->nullable();
            $table->time('breakfast_time')->nullable();
            $table->time('lunch_time')->nullable();
            $table->time('dinner_time')->nullable();
            $table->time('walk_time')->nullable();
            $table->string('walk_location')->nullable();

            $table->boolean('religion_preference')->boolean('false');
            $table->string('religion_preferred')->nullable();
            $table->boolean('gender_preference')->boolean('false');
            $table->string('gender_preferred')->nullable();
            $table->boolean('language_preference')->boolean('false');
            $table->string('language_preferred')->nullable();
            $table->boolean('age_preference')->boolean('false');
            $table->string('age_preferred')->nullable();
            $table->boolean('food_preference')->boolean('false');
            $table->string('food_preferred')->nullable();


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
        Schema::drop('patients');
    }
}
