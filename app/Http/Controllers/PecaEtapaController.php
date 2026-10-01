<?php

namespace App\Http\Controllers;

use App\Models\Etapa;
use App\Models\Peca;
use App\Models\PecaEtapa;
use App\Services\HistoricoService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PecaEtapaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ADICIONAR ETAPA
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, Peca $peca)
    {
        $dados = $request->validate([
            'etapa_id' => [
                'required',
                'exists:etapas,id',

                Rule::unique('peca_etapas', 'etapa_id')
                    ->where(
                        fn ($query) =>
                        $query->where('peca_id', $peca->id)
                    ),
            ],

            'descricao' => [
                'nullable',
                'string',
            ],

            'observacoes' => [
                'nullable',
                'string',
            ],
        ]);


        $etapa = Etapa::where('id', $dados['etapa_id'])
            ->where('ativo', true)
            ->firstOrFail();


        $pecaEtapa = PecaEtapa::create([
            'peca_id' => $peca->id,
            'etapa_id' => $etapa->id,
            'ordem' => $etapa->ordem,
            'descricao' => $dados['descricao'] ?? null,
            'observacoes' => $dados['observacoes'] ?? null,
        ]);


        HistoricoService::registrar(
            $peca,
            'ETAPA_ADICIONADA',
            "Adicionou a etapa {$etapa->nome}.",
            'peca_etapas',
            $pecaEtapa->id
        );


        return redirect()
            ->route('pecas.show', $peca)
            ->with(
                'sucesso',
                "Etapa {$etapa->nome} adicionada com sucesso."
            );
    }


    /*
    |--------------------------------------------------------------------------
    | TELA DE EDIÇÃO
    |--------------------------------------------------------------------------
    */

    public function edit(Peca $peca, Etapa $etapa)
    {
        $pecaEtapa = PecaEtapa::where(
            'peca_id',
            $peca->id
        )
        ->where(
            'etapa_id',
            $etapa->id
        )
        ->firstOrFail();


        return view(
            'pecas.etapas.edit',
            compact(
                'peca',
                'etapa',
                'pecaEtapa'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR ETAPA
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Peca $peca,
        Etapa $etapa
    ) {

        $pecaEtapa = PecaEtapa::where(
            'peca_id',
            $peca->id
        )
        ->where(
            'etapa_id',
            $etapa->id
        )
        ->firstOrFail();


        $dados = $request->validate([
            'descricao' => [
                'nullable',
                'string',
            ],

            'observacoes' => [
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


        if (
            ($pecaEtapa->descricao ?? '') !==
            ($dados['descricao'] ?? '')
        ) {
            $camposAlterados[] = 'descrição';
        }


        if (
            ($pecaEtapa->observacoes ?? '') !==
            ($dados['observacoes'] ?? '')
        ) {
            $camposAlterados[] = 'observações';
        }


        /*
        |--------------------------------------------------------------------------
        | ATUALIZA
        |--------------------------------------------------------------------------
        */

        $pecaEtapa->update([
            'descricao' => $dados['descricao'] ?? null,
            'observacoes' => $dados['observacoes'] ?? null,
        ]);


        /*
        |--------------------------------------------------------------------------
        | HISTÓRICO
        |--------------------------------------------------------------------------
        */

        if (count($camposAlterados) > 0) {

            HistoricoService::registrar(
                $peca,
                'ETAPA_EDITADA',
                'Alterou ' .
                    implode(', ', $camposAlterados) .
                    " da etapa {$etapa->nome}.",
                'peca_etapas',
                $pecaEtapa->id
            );
        }


        return redirect()
            ->route('pecas.show', $peca)
            ->with(
                'sucesso',
                "Etapa {$etapa->nome} atualizada com sucesso."
            );
    }
}