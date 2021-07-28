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
        DB::table('roles')->insert([
            'RoleName'=>'admin',
            'IsDeleted' => 0,
            'CreatedDate' => now()
        ]);
        DB::table('users')->insert([
            'ROLE_ID' =>'1',
            'username' => 'admin',
            'password' => bcrypt('123456'),
            'FirstName' => 'Web Admin',
            'LastName' => 'Web Admin',
            'email' => 'admin@yahoo.com',
            'IsDeleted' => 0,
            'CreatedDate' => now()
        ]);
        
    }
}
