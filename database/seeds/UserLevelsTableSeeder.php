<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserLevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_levels')->insert([
            'name' => 'regular',
            'can_publish' => false,
            'can_add_remove_contributors' => false,
            'can_edit' => false,
            'can_unpublish' => false,
            'can_ban' => false,
            'can_grant_revoke_author_privilege' => false,
            'can_grant_revoke_admin_privilege' => false,
            'can_grant_revoke_editor_privilege' => false,
            'can_grant_revoke_moderator_privilege' => false,
        ]);
        DB::table('user_levels')->insert([
            'name' => 'author',
            'can_publish' => true,
            'can_add_remove_contributors' => false,
            'can_edit' => false,
            'can_unpublish' => false,
            'can_ban' => false,
            'can_grant_revoke_author_privilege' => false,
            'can_grant_revoke_admin_privilege' => false,
            'can_grant_revoke_editor_privilege' => false,
            'can_grant_revoke_moderator_privilege' => false,
        ]);
        DB::table('user_levels')->insert([
            'name' => 'moderator',
            'can_publish' => true,
            'can_add_remove_contributors' => true,
            'can_edit' => true,
            'can_unpublish' => true,
            'can_ban' => true,
            'can_grant_revoke_author_privilege' => false,
            'can_grant_revoke_admin_privilege' => false,
            'can_grant_revoke_editor_privilege' => false,
            'can_grant_revoke_moderator_privilege' => false,
        ]);
        DB::table('user_levels')->insert([
            'name' => 'editor',
            'can_publish' => true,
            'can_add_remove_contributors' => true,
            'can_edit' => true,
            'can_unpublish' => true,
            'can_ban' => true,
            'can_grant_revoke_author_privilege' => true,
            'can_grant_revoke_admin_privilege' => false,
            'can_grant_revoke_editor_privilege' => false,
            'can_grant_revoke_moderator_privilege' => false,
        ]);
        DB::table('user_levels')->insert([
            'name' => 'admin',
            'can_publish' => true,
            'can_add_remove_contributors' => true,
            'can_edit' => true,
            'can_unpublish' => true,
            'can_ban' => true,
            'can_grant_revoke_author_privilege' => true,
            'can_grant_revoke_admin_privilege' => true,
            'can_grant_revoke_editor_privilege' => true,
            'can_grant_revoke_moderator_privilege' => true,
        ]);

    }
}
