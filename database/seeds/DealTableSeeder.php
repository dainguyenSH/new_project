<?php

use Illuminate\Database\Seeder;
use App\Deal;
use App\Merchant;

class DealTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('deals')->truncate();
        for ($i=0; $i < 100; $i++) { 
        	Deal::create(array(
				'merchants_id'     => array_rand(Merchant::select('id')->get()->toArray()),
				'name'     => rand(),
				'description'     => rand(),
				'apply_objects'     => rand(),
				'start_day'     => rand(),
				'end_day'     => rand(),
				'image_avatar'     => rand(),
				'image_content'     => rand(),
				'stores'     => rand(),
				'slug'     => rand(),
				'status'     => rand(0,3),
			));
        }
    }
}
