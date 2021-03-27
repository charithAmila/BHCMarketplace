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
                "address" => "0x41fFF2b6F20a6b7E9F27764f092264B30053D4d4",
                "ipfs_hash" => "empty",
                "type" => 721
            ],
            [
                "address" => "0x21EEd53b1d3e5Af80bfE15220625A3B66701277c",
                "ipfs_hash" => "empty",
                "type" => 1155
            ]
        ];
        DB::table('collections')->insert($collections);
    }
}
