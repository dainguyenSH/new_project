<?php

use Illuminate\Database\Seeder;
use App\Admin;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->truncate();

        Admin::create([
        	'name'=>'Admin', 
        	'user_name'=>'shoppie', 
        	'email'=>'admin@shoppie.vn', 
        	'password'=>Hash::make('12344321')
        ]);
    }
}
