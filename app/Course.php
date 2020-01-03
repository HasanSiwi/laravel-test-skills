<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $guarded = ["id"];

    public function courseTypes()
    {
        return $this->hasMany('App\CourseType');
    }
}
