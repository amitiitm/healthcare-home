<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreferredCaregiversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('preferred_caregivers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lead_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->decimal('points', 10, 2);
            $table->integer('status_id')->default(1);
            $table->integer('is_accepted')->default(0);
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
        Schema::drop('preferred_caregivers');
    }
}
