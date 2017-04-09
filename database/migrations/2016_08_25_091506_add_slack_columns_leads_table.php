<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSlackColumnsLeadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->string('slack_channel_name')->nullable();
            $table->timestamp('slack_channel_created_at')->nullable();
            $table->timestamp('slack_channel_deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['slack_channel_name','slack_channel_created_at','slack_channel_deleted_at']);
        });
    }
}
