<?php

namespace App\Http\Controllers;

use App\Proposta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropostaController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();
        $temas = json_decode($data["temas"]);
        $briefing = DB::select('SELECT Min(codbriefing) AS codbriefing FROM comercial.tblbriefing GROUP BY sigla HAVING (((sigla)=?));', [$data['sigla']]);

        foreach ($temas as $value)
        {
            $dataSet[] = [
                'sigla'                 => $data['sigla'],
                'ano'                   => '2020',
                'tema_escolhido'        => $value->temas,
                'codproposta'           => $briefing[0]->codbriefing,
                'sugerido_por'          => 'Cliente',
                'idtema'                => $value->id_tema,
            ];
        }
        Proposta::insert($dataSet);

        return response()->json('Visita encerrada e tema(s) acionado(s) ao briefing ' . $briefing[0]->codbriefing);
        // Proposta::created(array('name' => 'John'));
    }
}
