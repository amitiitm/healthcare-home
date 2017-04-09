<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCgReplacementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cg_replacement', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('employee_id');
            $table->integer('user_id');
            $table->integer('lead_id');
            $table->integer('complaint_id')->nullable();
            $table->date('start_date');
            $table->date('replacement_date');
            $table->text('reason');
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
        Schema::drop('cg_replacement');
    }
}
