<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBackupCgFlagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lead_vendors', function (Blueprint $table) {
            $table->boolean('is_primary')->default(false);
            $table->float('price_per_day')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lead_vendors', function (Blueprint $table) {
            $table->dropColumn(['is_primary','price_per_day']);
        });
    }
}
