<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAadharToUserVendors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('user_vendors', function($table) {
//            $table->string('aadhar')->default('');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
//        Schema::table('user_vendors', function($table) {
//            $table->dropColumn('aadhar');
//        });
    }
}
