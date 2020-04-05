<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ControladoShopping extends Model
{
    protected $table = 'producao.tbl_controlado_shopping';
    protected $primaryKey = ['num_requisicao', 'barcode'];
    public $incrementing = false;
    public $timestamps = false;
    protected $fillable = [
        'num_requisicao',
        'barcode'
    ];
}
