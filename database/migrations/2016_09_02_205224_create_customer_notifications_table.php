<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lead_id');
            $table->string('customer_phone');
            $table->string('header')->default('');
            $table->text('content')->default('');
            $table->timestamp('push_sent_at')->nullable();
            $table->timestamp('sms_sent_at')->nullable();
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
        Schema::drop('customer_notifications');
    }
}
