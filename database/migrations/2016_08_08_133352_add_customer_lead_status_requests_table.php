<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomerLeadStatusRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_lead_status_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lead_id');
            $table->integer('status_id');
            $table->integer('reason_id')->nullable();
            $table->integer('issue_id')->nullable();
            $table->string('other_info')->default('');
            $table->integer('user_id')->nullable();
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
        Schema::drop('customer_lead_status_requests');
    }
}
