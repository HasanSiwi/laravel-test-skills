<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\School;

class CampusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $schools = School::all();

        DB::table('campuses')->insert([
            'name' => Str::random(10),
            'school_id' => $schools->first()->id,
        ]);
    }
}
