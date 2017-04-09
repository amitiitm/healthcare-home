<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCgAssignmentEmailToCustomerFieldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dateTime('cg_assigned_at')->nullable();
            $table->dateTime('qc_assigned_at')->nullable();
            $table->dateTime('cg_assigned_notification_sent_at')->nullable();
            $table->dateTime('qc_assigned_notification_sent_at')->nullable();
            $table->integer('current_status')->default(0);
            $table->dateTime('current_status_change_at')->nullable();
            $table->dateTime('started_service_at')->nullable();
            $table->dateTime('start_service_mail_sent_at')->nullable();
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
            $table->dropColumn(['cg_assigned_at','cg_assigned_notification_sent_at','qc_assigned_at','qc_assigned_notification_sent_at','current_status','current_status_change_at','start_service_mail_sent_at','started_service_at']);
        });
    }
}
