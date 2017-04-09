<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSlackTimestampTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lead_comments', function (Blueprint $table) {
            $table->boolean('is_from_slack')->default(false);
            $table->string('slack_user')->nullable();
            $table->double('slack_timestamp')->nullable();
        });
        Schema::table('user_employees', function (Blueprint $table) {
            $table->string('slack_user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lead_comments', function (Blueprint $table) {
            $table->dropColumn(['is_from_slack','slack_user','slack_timestamp']);
        });
        Schema::table('user_employees', function (Blueprint $table) {
            $table->dropColumn(['slack_user_id']);
        });
    }
}
