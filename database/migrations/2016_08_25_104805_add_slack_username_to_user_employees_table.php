<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSlackUsernameToUserEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_employees', function (Blueprint $table) {
            $table->string('slack_username')->nullable();
            $table->timestamp('slack_invitation_send_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_employees', function (Blueprint $table) {
            $table->dropColumn(['slack_username','slack_invitation_send_at']);
        });
    }
}
