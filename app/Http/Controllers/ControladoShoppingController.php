<?php

namespace App\Http\Controllers;

use App\ControladoShopping;
use Illuminate\Http\Request;

class ControladoShoppingController extends Controller
{
    public function validar(Request $request)
    {
        $data = $request->all();
        $itens = json_decode($data['data'], true);
        $invalidos = array();

        foreach ($itens as $item){
            $controlados = ControladoShopping::Where(function ($query) use ($item){
                $query->whereNull('retorno')
                    ->where('barcode','ilike', $item['barcode']);
                })->count();
            if ($controlados == 1){
                array_push($invalidos, $item);
            }
        }
        return response()->json($invalidos);
    }

    public function adicionar(Request $request)
    {

        $data = $request->all();
        //$itens = json_decode($data);
        $records = [];
        /*foreach ($itens as $item){
            //$tema = ControladoShopping::create($item);
        }*/

        foreach($data as $item){

            $record = [
                'num_requisicao' => $item['num_requisicao'],
                'barcode' => $item['barcode']
            ];

            $records[] = $record;

        }


        ControladoShopping::insert($records);

        return response()->json($request->all());

    }
}
