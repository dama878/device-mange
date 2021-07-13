<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'username' => 'admin',
            'password' => bcrypt('123456'),
            'fullname' => 'Web Admin',
            'email' => 'admin@yahoo.com',
            'IsDeleted' => 0,
            'CreatedDate' => now()
        ]);
    }
}
