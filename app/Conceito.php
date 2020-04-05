<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conceito extends Model
{
    public function tema()
    {
        return $this->hasMany(Tema::class);
    }
}
