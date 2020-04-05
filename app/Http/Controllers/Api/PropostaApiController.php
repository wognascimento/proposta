<?php

namespace App\Http\Controllers\Api;

use App\Models\Proposta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PropostaApiController extends Controller
{

    public function index()
    {
        return response()->json(['error' => 'index Unauthorized'], 401);
    }

    public function create()
    {
        return response()->json(['error' => 'create Unauthorized'], 401);
    }

    public function store(Request $request)
    {
		try {
			$data = $request->all();
			$proposta = new Proposta();
			$proposta->cliente = $data['cliente'];
			$proposta->tema = $data['tema'];
			dd($request->file('file'));
			$proposta->file = base64_encode(file_get_contents($request->file('file')->path()));
			$proposta->save();
		} catch(Exception $ex) {
			throw new Exception($ex);
		}
        return response()->json($proposta, 201);
    }

    public function show($id)
    {
        $proposta = Proposta::find($id);
        return response()->json($proposta, 201);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $proposta = Proposta::find($id);
        $proposta->delete();
        return response()->json($proposta, 201);
    }

    public function propostas(Request $request)
    {
        $data = $request->all();
        $propostas = Proposta::where('cliente', $data['cliente'])
            ->orderBy('id', 'asc')
            ->get();

        return response()->json($propostas, 201);
    }
}
