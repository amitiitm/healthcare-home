<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserEmployeeDeprtmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       /* Schema::create('user_employee_departments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id');
            $table->integer('department_id');
            $table->softDeletes();
            $table->timestamps();
        });*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*Schema::drop('user_employee_departments');*/
    }
}
