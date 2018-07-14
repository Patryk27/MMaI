<?php

use Illuminate\Database\Seeder as BaseSeeder;

class Seeder extends BaseSeeder
{

    /**
     * @param string|string[] $tables
     * @return void
     */
    protected function truncate($tables): void
    {
        if (!is_array($tables)) {
            $tables = [$tables];
        }

//        DB::statement('SET foreign_key_checks=0'); @todo

        foreach ($tables as $table) {
//            DB::table($table)->truncate();
        }

//        DB::statement('SET foreign_key_checks=1');
    }

}