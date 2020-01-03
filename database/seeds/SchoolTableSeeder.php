<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SchoolTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('schools')->insert([
            'name' => Str::random(10),
            'email' => Str::random(10).'@gmail.com',
            'logo' => Str::random(5).'.png',
        ]);
    }
}
