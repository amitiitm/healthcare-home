<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComplaintsStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('complaints_status')->insert([
            'label' => "Pending",
        ]);
        DB::table('complaints_status')->insert([
            'label' => "Resolved",
        ]);

    }
}
