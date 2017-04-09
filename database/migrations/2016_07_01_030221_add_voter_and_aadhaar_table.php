<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVoterAndAadhaarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_vendors', function (Blueprint $table) {
            $table->string('voter')->nullable();
            $table->string('aadhar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_vendors', function (Blueprint $table) {
            $table->dropColumn(['voter','aadhar']);
        });
    }
}
