<?php

namespace App\Http\Controllers;

use App\Models\Etapa;
use App\Models\Medida;
use App\Models\Peca;
use App\Services\HistoricoService;
use Illuminate\Http\Request;

class MedidaController extends Controller
{
    /*
|--------------------------------------------------------------------------
| TELA DE EDIÇÃO
|--------------------------------------------------------------------------
*/

public function edit(Peca $peca, Medida $medida)
{
    if ((int) $medida->peca_id !== (int) $peca->id) {
        abort(404);
    }

    $medida->load('etapa');

    return view(
        'pecas.medidas.edit',
        compact(
            'peca',
            'medida'
        )
    );
}


/*
|--------------------------------------------------------------------------
| ATUALIZAR MEDIDA
|--------------------------------------------------------------------------
*/

public function update(
    Request $request,
    Peca $peca,
    Medida $medida
) {
    if ((int) $medida->peca_id !== (int) $peca->id) {
        abort(404);
    }


    $dados = $request->validate([
        'nome' => [
            'required',
            'string',
            'max:100',
        ],

        'valor' => [
            'nullable',
            'numeric',
            'min:0',
        ],

        'unidade' => [
            'nullable',
            'string',
            'max:20',
        ],

        'observacao' => [
            'nullable',
            'string',
        ],
    ]);


    /*
    |--------------------------------------------------------------------------
    | DESCOBRE O QUE FOI ALTERADO
    |--------------------------------------------------------------------------
    */

    $camposAlterados = [];


    if ($medida->nome !== trim($dados['nome'])) {
        $camposAlterados[] = 'nome';
    }


    $valorAtual =
        $medida->valor === null
            ? null
            : (float) $medida->valor;


    $novoValor =
        isset($dados['valor'])
            ? (float) $dados['valor']
            : null;


    if ($valorAtual !== $novoValor) {
        $camposAlterados[] = 'valor';
    }


    if (
        ($medida->unidade ?? '') !==
        ($dados['unidade'] ?? '')
    ) {
        $camposAlterados[] = 'unidade';
    }


    if (
        ($medida->observacao ?? '') !==
        ($dados['observacao'] ?? '')
    ) {
        $camposAlterados[] = 'observação';
    }


    $nomeAnterior = $medida->nome;


    /*
    |--------------------------------------------------------------------------
    | ATUALIZA
    |--------------------------------------------------------------------------
    */

    $medida->update([
        'nome' => trim($dados['nome']),
        'valor' => $dados['valor'] ?? null,
        'unidade' => $dados['unidade'] ?? 'cm',
        'observacao' => $dados['observacao'] ?? null,
    ]);


    /*
    |--------------------------------------------------------------------------
    | HISTÓRICO
    |--------------------------------------------------------------------------
    */

    if (count($camposAlterados) > 0) {

        HistoricoService::registrar(
            $peca,
            'MEDIDA_EDITADA',
            'Alterou ' .
                implode(', ', $camposAlterados) .
                " da medida {$nomeAnterior}.",
            'medidas',
            $medida->id
        );
    }


    return redirect()
        ->route('pecas.show', $peca)
        ->with(
            'sucesso',
            'Medida atualizada com sucesso.'
        );
}

    public function store(Request $request, Peca $peca)
    {
        $dados = $request->validate([
            'etapa_id' => [
                'nullable',
                'exists:etapas,id',
            ],

            'nome' => [
                'required',
                'string',
                'max:100',
            ],

            'valor' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'unidade' => [
                'nullable',
                'string',
                'max:20',
            ],

            'observacao' => [
                'nullable',
                'string',
            ],
            
        ]);


        /*
        |--------------------------------------------------------------------------
        | ETAPA OPCIONAL
        |--------------------------------------------------------------------------
        */

        $etapaId = $dados['etapa_id'] ?? null;


        /*
        |--------------------------------------------------------------------------
        | CONFIRMA SE A ETAPA PERTENCE À PEÇA
        |--------------------------------------------------------------------------
        */

        if ($etapaId) {

            $etapaPertenceAPeca = $peca->etapas()
                ->where(
                    'etapas.id',
                    $etapaId
                )
                ->exists();


            if (!$etapaPertenceAPeca) {

                return back()->withErrors([
                    'nome' =>
                        'A etapa selecionada não pertence a este modelo.',
                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | CRIA A MEDIDA
        |--------------------------------------------------------------------------
        */

        $medida = Medida::create([
            'peca_id' => $peca->id,

            'etapa_id' => $etapaId,

            'nome' => trim($dados['nome']),

            'valor' => $dados['valor'] ?? null,

            'unidade' => $dados['unidade'] ?? 'cm',

            'observacao' => $dados['observacao'] ?? null,
        ]);


        /*
        |--------------------------------------------------------------------------
        | HISTÓRICO
        |--------------------------------------------------------------------------
        */

        if ($etapaId) {

            $etapa = Etapa::find($etapaId);

            $descricaoHistorico =
                "Adicionou a medida {$medida->nome} " .
                "na etapa {$etapa->nome}.";

        } else {

            $descricaoHistorico =
                "Adicionou a medida {$medida->nome} ao modelo.";
        }


        HistoricoService::registrar(
            $peca,
            'MEDIDA_ADICIONADA',
            $descricaoHistorico,
            'medidas',
            $medida->id
        );


        return redirect()
            ->route('pecas.show', $peca)
            ->with(
                'sucesso',
                'Medida adicionada com sucesso.'
            );
    }
}