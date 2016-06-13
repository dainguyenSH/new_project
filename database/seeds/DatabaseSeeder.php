<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(OptionTableSeeder::class);
        $this->call(OptionValueTableSeeder::class);
        $this->call(ProvinceTableSeeder::class);
        $this->call(DistrictTableSeeder::class);
        // $this->call(MerchantTableSeeder::class);
        $this->call(AdminTableSeeder::class);
        // $this->call(Statical::class);
        
        Model::reguard();
    }
}
