<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
	        'first_name' => 'UPSC',
	        'last_name' => 'Admin',
	        'email' => 'admin@upsc.com',
	        'password' => Hash::make('#admin123'),
	        'is_admin' => 1,
	        'email_verified_at' => date('Y-m-d H:i:s'),
	        'is_active' => 1,
	        'created_at' => date('Y-m-d H:i:s'),
	        'updated_at' => date('Y-m-d H:i:s'),
	    ]);
    }
}
