<?php

use Illuminate\Database\Seeder;
use App\Merchant;

class MerchantTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('merchants')->truncate();
  //       Merchant::create(array(
		// 	'user_name' =>'kfc',
		// 	'email'     =>'kfc@shoppie.vn',
		// 	'password'  =>Hash::make('kfckfc'),
		// 	'name'      =>'KFC',
		// 	'logo'      =>'images/logo/kfc.png',
		// 	'active'    =>1,
		// 	'kind'      =>4 //dang nhap = username va password
		// ));	

		// Merchant::create(array(
		// 	'user_name' =>'lotte',
		// 	'email'     =>'lotte@shoppie.vn',
		// 	'password'  =>Hash::make('lottelotte'),
		// 	'name'      =>'Lotte Cinema',
		// 	'logo'      =>'images/logo/lotte.png',
		// 	'active'    =>1,
		// 	'kind'      =>4 //dang nhap = username va password
		// ));	
		// Merchant::create(array(
		// 	'user_name' =>'alfresco',
		// 	'email'     =>'alfresco@shoppie.vn',
		// 	'password'  =>Hash::make('alfrescoalfresco'),
		// 	'name'      =>'The Alfresco Group',
		// 	'logo'      =>'images/logo/alfresco.png',
		// 	'active'    =>1,
		// 	'kind'      =>4 //dang nhap = user name va password
		// ));	

		// Merchant::create(array(
		// 	'user_name' =>'pico',
		// 	'email'     =>'pico@shoppie.vn',
		// 	'password'  =>Hash::make('picopico'),
		// 	'name'      =>'Pico',
		// 	'logo'      =>'images/logo/pico.png',
		// 	'active'    =>1,
		// 	'kind'      =>4 //dang nhap = user name va password
		// ));	

		// Merchant::create(array(
		// 	'user_name' =>'gcoin',
		// 	'email'     =>'gcoin@shoppie.vn',
		// 	'password'  =>Hash::make('gcoingcoin'),
		// 	'name'      =>'Gcoin',
		// 	'logo'      =>'images/logo/gcoin.png'
		// 	'active'    =>1,
		// 	'kind'      =>4 //dang nhap = user name va password
		// ));	

		// Merchant::create(array(
		// 	'user_name' =>'cgv',
		// 	'email'     =>'cgv@shoppie.vn',
		// 	'password'  =>Hash::make('cgvcgv'),
		// 	'name'      =>'CGV - Megastar',
		// 	'logo'      =>'images/logo/cgv.png',
		// 	'active'    =>1,
		// 	'kind'      =>4 //dang nhap = user name va password
		// ));	

		// Merchant::create(array(
		// 	'user_name' =>'aeon',
		// 	'email'     =>'aeon@shoppie.vn',
		// 	'password'  =>Hash::make('aeonaeon'),
		// 	'name'      =>'AEON',
		// 	'active'    =>1,
		// 	'kind'      =>4 //dang nhap = user name va password
		// ));	

		
    }
}
