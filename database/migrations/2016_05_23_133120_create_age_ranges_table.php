<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgeRangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('age_ranges', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label');
            $table->integer('min_age');
            $table->integer('max_age');
            $table->boolean('show_on_front')->default(false);
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
        Schema::drop('age_ranges');
    }
}
