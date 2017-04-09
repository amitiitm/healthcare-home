<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_levels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            // Author
            $table->boolean('can_publish');
            // Moderator
            $table->boolean('can_add_remove_contributors');
            $table->boolean('can_edit');
            $table->boolean('can_unpublish');
            $table->boolean('can_ban');
            // Editor
            $table->boolean('can_grant_revoke_author_privilege');
            // Admin
            $table->boolean('can_grant_revoke_admin_privilege');
            $table->boolean('can_grant_revoke_editor_privilege');
            $table->boolean('can_grant_revoke_moderator_privilege');
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
        Schema::drop('user_levels');
    }
}
