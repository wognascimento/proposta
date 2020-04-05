<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tema extends Model
{
    protected $table = 'catalogo_temas';
    protected $primaryKey = 'idtema';
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = [
        'idtema',
        'temas',
        'tema_interno',
        'inic',
        'estoque',
        'chance_combinada',
        'fechado',
        'disponivel',
        'descricao',
        'orient_catalogo',
        'temas_novos_2007',
        'ano',
        'conceito',
        'capa',
        'youtube',
        'apresentacao'
    ];
    /*
    public function conceitos()
    {
        return $this->hasMany(Conceito::class);
    }
    */
}
