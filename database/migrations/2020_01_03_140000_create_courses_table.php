<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table)
        {
            $table->bigIncrements('id');

            $table->string('name');
            $table->bigInteger('campus_id')->unsigned();
            $table->bigInteger('course_type_id')->unsigned();
            $table->string('price');

            $table->timestamps();

            $table->foreign('campus_id')->references('id')->on('campuses');
            $table->foreign('course_type_id')->references('id')->on('course_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
}
