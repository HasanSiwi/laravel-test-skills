<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Campus;
use App\CourseType;

class CourseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $campuses = Campus::all();
        $course_types = CourseType::all();

        DB::table('courses')->insert([
            'name' => Str::random(10),
            'campus_id' => $campuses->first()->id,
            'course_type_id' => $course_types->first()->id,
            'price' => '100',
        ]);
    }
}
