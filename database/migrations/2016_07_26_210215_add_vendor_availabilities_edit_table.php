<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVendorAvailabilitiesEditTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_availabilities', function (Blueprint $table) {
            $table->timestamp('available_date')->nullable();
            $table->string('other_reason')->default("");
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
            $table->dropColumn(['available_date','other_reason']);
        });
    }
}
