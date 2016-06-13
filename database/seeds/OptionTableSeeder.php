<?php

use Illuminate\Database\Seeder;
use App\Option;
class OptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('options')->truncate();
		Option::create(array(
			'name'     => 'Lĩnh vực',
		));
		
		Option::create(array(
			'name'     => 'Package',
		));

		Option::create(array(
			'name'     => 'Card level',
		));

		Option::create(array(
			'name'     => 'Card chops chops',
		));
		
		Option::create(array(
			'name'     => 'Month',
		));
    }
}
