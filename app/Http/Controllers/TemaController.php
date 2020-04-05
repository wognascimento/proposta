<?php

namespace App\Http\Controllers;

use App\DisponibilidadeTema;
use App\Tema;
use App\TodosTemas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TemaController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->all();
        $valor = $data['dados'];
        $temas =  DisponibilidadeTema::Where(function ($query) use ($valor){
                $query->orWhere('temas','like', '%'. $valor . '%')
                    ->orWhere('descricao','like', '%'.$valor . '%')
                    ->orWhere('orient_catalogo','like', '%'.$valor . '%')
                    ->orWhere('temas_novos_2007','like', "%$valor%")
                    ->orWhere('conceito','like', "%$valor%")
                    ->orWhere('ano','like', "%$valor%");
            })
            ->orderBy('temas', 'asc')
            ->get();
        return response()->json($temas);
    }

    public function sugestaoAtendimento(Request $request)
    {
        $data = $request->all();
        $dataSugeridos = DB::select('SELECT idtema FROM sugestao_tema_atendimento WHERE sigla =?;', [$data['sigla']]);
        $arrayEmails = [];
        foreach ($dataSugeridos as $row) {
            $arrayEmails[] = $row->idtema;
        };
        $temas =  DisponibilidadeTema::whereIn('idtema', $arrayEmails)
            ->orderBy('temas', 'asc')
            ->get();
        return response()->json($temas);
    }

    public function inicial()
    {
        $temas =  DisponibilidadeTema::Where('ano', '>=', 2019)
            ->orderBy('temas', 'asc')
            ->get();
        return response()->json($temas);
    }

    public function conceito(Request $request)
    {
        $data = $request->all();
        $valor = json_decode( $data['dados'] );
        $temas =  DisponibilidadeTema::Where(function ($query) use ($valor){
            foreach ($valor as $item) {
                $query->orWhere('conceito','like', "%$item->conceito%");
            }
        })
        ->orderBy('temas', 'asc')
        ->get();
        return response()->json($temas);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $fileCapa = $request->file('capa');
        $fileApresentacao = $request->file('apresentacao');
        $tema = Tema::find($data['idtema']);

        if (is_null($fileCapa) or is_null($fileApresentacao))
            return response()->json('Capa e pdf são obrigatórios!',400);

        if (!is_null($fileCapa) and $fileCapa->isValid())
        {
            $nameCapa = $fileCapa->getClientOriginalName();
            if ($tema)
            {
                $capa = explode("/", $tema->capa);
                Storage::delete("{$capa[5]}/{$capa[6]}/{$capa[7]}");
            }
            $upload = $fileCapa->storeAs('img/'.$data['idtema'], $nameCapa);
            $data['capa'] = $request->getSchemeAndHttpHost().'/api-rest/public/img/'.$data['idtema'].'/'.$nameCapa;
        }

        if (!is_null($fileApresentacao) and $fileApresentacao->isValid())
        {
            $nameApresentacao = $fileApresentacao->getClientOriginalName();
            if ($tema)
            {
                $apresentacao = explode("/", $tema->apresentacao);
                Storage::delete("{$apresentacao[5]}/{$apresentacao[6]}/{$apresentacao[7]}");
            }

            $upload = $fileApresentacao->storeAs('img/'.$data['idtema'], $nameApresentacao);
            $data['apresentacao'] = $request->getSchemeAndHttpHost().'/api-rest/public/img/'.$data['idtema'].'/'.$nameApresentacao;
        }


        $data['conceito'] =  json_encode(explode( "\n", $data['conceito'] )) ;


        if (!$tema)
        {
            $tema = new Tema;
            $tema->idtema = $data['idtema'];
        }
        $tema->capa = $data['capa'];
        $tema->apresentacao = $data['apresentacao'];
        $tema->temas = $data['temas'];
        $tema->descricao = $data['descricao'];
        $tema->orient_catalogo = $data['orient_catalogo'];
        $tema->temas_novos_2007 = $data['temas_novos_2007'];
        $tema->ano = $data['ano'];
        $tema->conceito = $data['conceito'];
        $tema->youtube = $data['youtube'];
        $tema->save();

        return response()->json($tema);

    }

    public function getTemaCatalogo($id){
        return response()->json(Tema::findOrFail($id));
    }

    public function getTemas()
    {
        return response()->json(TodosTemas::all());
    }

    public function arrumarConceito()
    {
        $temas = Tema::orderBy('idtema', 'asc')->get();
        foreach($temas as $tema)
        {
            $tema['conceito'] = json_encode(explode( "\n", $tema['conceito'] ), JSON_UNESCAPED_UNICODE);
            $tema->save();
        }
        return response()->json(['Conceitos atualizados']);
    }

    public function agendaShowroom(){
        $data = DB::select('SELECT t_agenda_sr.data_visita, t_agenda_sr.sigla, clientes.nome, reg_atend
                                   FROM clientes INNER JOIN t_agenda_sr ON clientes.sigla = t_agenda_sr.sigla
                                   ORDER BY t_agenda_sr.sigla;');

        return response()->json(['agenda'=> $data]);
    }

    public function conceitos()
    {
        $conceitos =  DB::select('SELECT conceito_tema.conceito, "" AS checked FROM conceito_tema
                                         INNER JOIN catalogo_temas ON conceito_tema.idtema = catalogo_temas.idtema
                                         GROUP BY conceito_tema.conceito
                                         ORDER BY conceito_tema.conceito;');
        return response()->json(['conceitos'=> $conceitos]);
    }

    public function temaSelecionado($id)
    {
        $tema = Tema::find($id);
        return response()->json($tema);
    }

}
