<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use avaa\User;
class DatabaseSeeder extends Seeder
{
   
    public function run()
    {
        $this->truncateTables([
    
            'mentores',
            'users',
        ]);

    	factory(User::class,80)->create();
        
    }

    public function truncateTables(array $tables)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}
