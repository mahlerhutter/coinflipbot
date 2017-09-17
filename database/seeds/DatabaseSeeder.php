<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		DB::table('cities')->insert(
			['name' => str_random(10) ] 
			); 
		DB::table('cities')->insert(
			['name' => 'split' ] 
			); 
		DB::table('cities')->insert(
			['name' => 'razanac' ] 
			); 
		DB::table('cities')->insert(
			['name' => 'zadar' ] 
			); 
		DB::table('cities')->insert(
			['name' => 'dubrovnik' ] 
			); 
		DB::table('cities')->insert(
			['name' => 'zagreb' ] 
			); 


  }
}
