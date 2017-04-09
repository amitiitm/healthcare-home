<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComplaintsCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('complaints_categories')->insert([
            'name' => 'Customer Complaints',
            'parent_id' => false,
            'for_user' => false,
            'for_cg' => true
        ]);
        DB::table('complaints_categories')->insert([
            'name' => 'Caregiver Complaints',
            'parent_id' => false,
            'for_user' => true,
            'for_cg' => false
        ]);
        DB::table('complaints_categories')->insert([
            'name' => 'Back Office Complaints',
            'parent_id' => false,
            'for_user' => true,
            'for_cg' => true
        ]);
        DB::table('complaints_categories')->insert([
            'name' => 'Behaviour Related Issue',
            'parent_id' => 1,
            'for_user' => false,
            'for_cg' => true
        ]);

    }
}
