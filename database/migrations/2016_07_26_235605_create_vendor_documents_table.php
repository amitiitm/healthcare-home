<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_documents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mime')->default('');
            $table->integer('vendor_id')->nullable();
            $table->integer('document_type_id');
            $table->string('caption')->default("");
            $table->string('filename')->default("");

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
        Schema::drop('vendor_documents');
    }
}
