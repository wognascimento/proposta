<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DisponibilidadeTema extends Model
{
    protected $table = 'catalogo_temas';
    protected $primaryKey = 'idtema';
    public $timestamps = false;
    public $incrementing = false;
}
