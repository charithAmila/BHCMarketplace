<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LegendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$legends = [
    		['legend' => 'immortal', 'icon' => 'fa fa-star'],
            ['legend' => 'seasonal', 'icon' => 'fa fa-heart'],
            ['legend' => 'legendary', 'icon' => 'fa fa-bolt'],
            ['legend' => 'ancient', 'icon' => 'fa fa-circle'],
            ['legend' => 'common', 'icon' => 'fa fa-circle'],
            ['legend' => 'uncommon', 'icon' => 'fa fa-circle']
    	];
        DB::table('legends')->insert($legends);
    }
}
