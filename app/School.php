<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    public function campuses()
    {
        return $this->hasOne('App\Campus');
    }
}

