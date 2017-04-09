<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorsRatingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors_rating', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('working_days');
            $table->integer('present_days');
            $table->decimal('present_percent', 5, 2);
            $table->integer('absent_days');
            $table->decimal('absent_percent', 5, 2);
            $table->integer('uninformed_absents');
            $table->decimal('uninformed_absents_percent', 5, 2);
            $table->integer('punctuality_complaints');
            $table->integer('pre_placement_complaints');
            $table->text('comment')->default('');
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
        Schema::drop('vendors_rating');
    }
}
