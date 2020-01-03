<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    protected $guarded = ["id"];

    public function school()
    {
        return $this->belongsTo('App\School');
    }
}
