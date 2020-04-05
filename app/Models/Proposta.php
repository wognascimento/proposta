<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proposta extends Model
{
    protected $fillable = ['cliente', 'tema', 'file'];
}
