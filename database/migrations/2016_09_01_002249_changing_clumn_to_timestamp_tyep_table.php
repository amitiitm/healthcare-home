<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangingClumnToTimestampTyepTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lead_watchers', function (Blueprint $table) {
            $table->timestamp('slack_invitation_sent_at')->nullable();
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
            $table->dropColumn(['slack_invitation_sent_at']);
        });
    }
}
