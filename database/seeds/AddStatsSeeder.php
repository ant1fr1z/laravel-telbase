<?php

use Illuminate\Database\Seeder;

class AddStatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('statistics')->insert([
            'key' => 'total_count'
        ]);

        DB::table('statistics')->insert([
            'key' => 'day_count'
        ]);

        DB::table('statistics')->insert([
            'key' => 'week_count'
        ]);

        DB::table('statistics')->insert([
            'key' => 'month_count'
        ]);

    }
}
