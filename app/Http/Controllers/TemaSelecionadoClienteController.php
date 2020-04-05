<?php

namespace App\Http\Controllers;

use App\TemaSelecionadoCliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TemaSelecionadoClienteController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();

        $verificados = DB::select('SELECT reg_atend FROM clientes
                                          INNER JOIN tema_selecionado_cliente ON clientes.sigla = tema_selecionado_cliente.sigla
                                          INNER JOIN catalogo_temas ON tema_selecionado_cliente.id_tema = catalogo_temas.idtema
                                          WHERE reg_atend = ? AND tema_interno = ?;', [$data['reg_atend'], $data['tema_interno']]);

        if (count($verificados) > 0)
            return response()->json('conflito');

        $verificados = DB::select('SELECT reg_atend FROM clientes INNER JOIN tema_selecionado_cliente ON clientes.sigla = tema_selecionado_cliente.sigla WHERE reg_atend = ? AND id_tema = ?', [$data['reg_atend'], $data['id_tema']]);
        if (count($verificados) > 0)
            return response()->json('conflito');

        $verificados =  TemaSelecionadoCliente::Where(function ($query) use ($data){
            $query->Where('id_tema','=', $data['id_tema'])
                ->Where('sigla','=', $data['sigla']);
        })->get();

        if (count($verificados) > 0)
            return response()->json('existe');


        $tema = TemaSelecionadoCliente::create($data);

        return response()->json($tema);
    }

    public function temasSelecionados(Request $request)
    {

        $data = $request->all();
        $tema = DB::select('SELECT id, id_tema, temas, sigla
                           FROM tema_selecionado_cliente
                           INNER JOIN catalogo_temas ON tema_selecionado_cliente.id_tema = catalogo_temas.idtema
                           WHERE sigla = ?;', [$data['sigla']]);

        return response()->json($tema);
    }

    public function destroy($id)
    {
        $res = TemaSelecionadoCliente::destroy($id);
        return response()->json($res);
    }

}
