<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsersVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_vendors', function (Blueprint $table) {
            $table->timestamp('training_attended_date')->nullable();
            $table->integer('training_not_attended_reason_id')->nullable();
            $table->string('training_not_attended_other_reason')->default('');
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
            $table->dropColumn(['training_attended_date','training_not_attended_reason_id','training_not_attended_other_reason']);
        });
    }
}
