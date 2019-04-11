<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
        	'name' => 'Pedro',
        	'last_name' => 'Perez',
        	'email' => 'pperez@gmail.com',
        	'password' => bcrypt('123456'),
        	'rol' => 'becario',
        	'cedula' => '123456'

        ]);
    }
}
