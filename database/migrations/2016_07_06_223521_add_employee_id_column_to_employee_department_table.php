<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmployeeIdColumnToEmployeeDepartmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_departments', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->integer('employee_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_departments', function (Blueprint $table) {
            $table->dropColumn('employee_id');
            $table->integer('user_id');
        });
    }
}
