<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_vendors', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('user_id')->nullable();
            $table->string('gender')->nullable();
            $table->integer('employee_category_id')->nullable();
            $table->integer('weight')->nullable();
            $table->integer('age')->nullable();
            $table->integer('height')->nullable();
            $table->integer('religion_id')->nullable();
            $table->integer('food_type_id')->nullable();
            $table->integer('locality_id')->nullable();
            $table->string('address')->nullable();
            $table->string('alternate_no')->nullable();
            $table->boolean('has_smart_phone')->nullable();
            $table->boolean('has_bank_account')->nullable();
            $table->integer('qualification_id')->nullable();
            $table->boolean('training_attended')->nullable();
            $table->integer('training_id')->nullable();
            $table->string('experience')->nullable();
            $table->integer('preferred_shift_id')->nullable();
            $table->boolean('location_of_work')->nullable();
            $table->integer('agency_id')->nullable();
            $table->boolean('security_check')->nullable();
            $table->boolean('is_active')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_vendors');
    }
}
