<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemaSelecionadoCliente extends Model
{
    protected $table = 'tema_selecionado_cliente';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
        'id_tema', 'sigla'
    ];
}
