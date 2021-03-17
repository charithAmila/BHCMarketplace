<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $collections = [
            [
            'image' => 'default/logo.png',
            'display_name' => 'Happiness',
            'symbol' => 'HPS',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus bibendum placerat mauris, sed luctus dui eleifend a. Nunc in aliquet nunc, sit amet porta quam. Fusce blandit nibh id sollicitudin mattis. Proin urna nisl, efficitur convallis rhoncus at, elementum id neque.',
            'short_url' => 'happiness',
            ],
            [
                'image' => 'default/logo.png',
                'display_name' => 'Billion',
                'symbol' => 'BHC',
                'description' => 'Fusce at accumsan risus, ac ornare nibh. Vivamus at ipsum eu tortor feugiat interdum sit amet vel eros. Curabitur quis fermentum odio, sit amet mollis felis. Suspendisse potenti. In varius erat eget libero vehicula, ut sollicitudin diam maximus. Nullam porta volutpat elit, quis volutpat enim elementum in. Quisque auctor dolor vel condimentum finibus.',
                'short_url' => 'billion',
            ]
        ];
        DB::table('collections')->insert($collections);
        
    }
}
