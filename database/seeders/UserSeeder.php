<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'wallet' => Str::random(20),
            'display_photo' => 'default.png',
            'cover_photo' => 'default.jpeg',
        ]);
    }
}
