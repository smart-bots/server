<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use SmartBots\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$faker = Faker\Factory::create();
        for ($i=0;$i<10;$i++)
        {
            User::create([
            	'username' => $faker->username,
            	'name' => $faker->name,
            	'email' => $faker->email,
            	'phone' => $faker->phoneNumber,
            	'password' => Hash::make($faker->password),
                'avatar' => 'http://loremflickr.com/200/200/?'.str_random(5)
            ]);
        }
    }
}
