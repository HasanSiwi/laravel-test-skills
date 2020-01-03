<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseType extends Model
{
    protected $guarded = ["id"];

    public function courses()
    {
        return $this->belongsTo('App\Course');
    }
}
