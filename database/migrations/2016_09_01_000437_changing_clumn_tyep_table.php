<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangingClumnTyepTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lead_watchers', function (Blueprint $table) {
            $table->dropColumn(['slack_invitation_sent_at']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lead_watchers', function (Blueprint $table) {
            $table->integer('slack_invitation_sent_at');
        });
    }
}
