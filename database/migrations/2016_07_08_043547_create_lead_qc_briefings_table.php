<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadQcBriefingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lead_qc_briefings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lead_qc_id');
            $table->integer('task_id');
            $table->integer('brief_by_user_id');
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
        Schema::drop('lead_qc_briefings');
    }
}
