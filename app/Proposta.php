<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proposta extends Model
{
    protected $table = 'comercial.propostas';
    protected $primaryKey = ['tema_escolhido', 'sigla', 'ano', 'codproposta'];
    public $timestamps = false;
    public $incrementing = false;
    protected $fillable = ['sigla', 'ano', 'tema_escolhido', 'codproposta', 'sugerido_por', 'idtema'];
}
