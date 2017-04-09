<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVendorAvailabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_availabilities', function (Blueprint $table) {
            $table->integer('option_id')->nullable();
            $table->dropColumn(['available_date','other_reason']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_availabilities', function (Blueprint $table) {
            $table->dropColumn(['option_id']);
            $table->timestamp('available_date');
            $table->string('other_reason')->default("");
        });
    }
}
