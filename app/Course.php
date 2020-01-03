<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public function courseTypes()
    {
        return $this->hasMany('App\CourseType');
    }
}
