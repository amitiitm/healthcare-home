<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('service_id');
            $table->integer('lead_status_id')->default(1);
            $table->integer('service_sub_category_id')->nuallble();
            $table->string('customer_name');
            $table->integer('locality_id');
            $table->string('address')->nullable();
            $table->integer('city_id')->nullable();
            $table->text('email')->nullable();
            $table->string('reference_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('source_id')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('close_date')->nullable();
            $table->integer('number_of_days')->nullable();



            $table->integer('payment_type_id')->nullable();
            $table->integer('payment_period_id')->nullable();
            $table->integer('payment_mode_id')->nullable();

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
        Schema::drop('leads');
    }
}
