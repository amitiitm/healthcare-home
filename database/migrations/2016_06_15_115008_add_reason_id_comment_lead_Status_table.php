<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReasonIdCommentLeadStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lead_status', function (Blueprint $table) {
            $table->text('comment')->nullable();
            $table->text('data')->nullable();
            $table->integer('reason_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lead_status', function (Blueprint $table) {
            $table->dropColumn(['comment','data','reason_id']);
        });
    }
}
