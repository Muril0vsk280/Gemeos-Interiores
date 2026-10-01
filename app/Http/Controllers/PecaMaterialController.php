<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Peca;
use App\Models\PecaMaterial;
use App\Services\HistoricoService;
use Illuminate\Http\Request;

class PecaMaterialController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ADICIONAR MATERIAL
    |--------------------------------------------------------------------------
    */

    public function store(Request $request, Peca $peca)
    {
        $dados = $request->validate([
            'etapa_id' => [
                'required',
                'exists:etapas,id',
            ],

            'material_nome' => [
                'required',
                'string',
                'max:150',
            ],

            'quantidade' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'unidade' => [
                'nullable',
                'string',
                'max:30',
            ],

            'observacao' => [
                'nullable',
                'string',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | CONFIRMA SE A ETAPA PERTENCE À PEÇA
        |--------------------------------------------------------------------------
        */

        $etapaPertenceAPeca = $peca->etapas()
            ->where(
                'etapas.id',
                $dados['etapa_id']
            )
            ->exists();


        if (!$etapaPertenceAPeca) {

            return back()->withErrors([
                'material_nome' =>
                    'A etapa selecionada não pertence a este modelo.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | MATERIAL
        |--------------------------------------------------------------------------
        */

        $material = Material::firstOrCreate(
            [
                'nome' => trim($dados['material_nome']),
            ],
            [
                'unidade' => $dados['unidade'] ?? null,
                'ativo' => true,
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | VINCULA O MATERIAL À PEÇA
        |--------------------------------------------------------------------------
        */

        $item = PecaMaterial::updateOrCreate(
            [
                'peca_id' => $peca->id,
                'material_id' => $material->id,
                'etapa_id' => $dados['etapa_id'],
            ],
            [
                'quantidade' => $dados['quantidade'] ?? null,
                'unidade' => $dados['unidade'] ?? null,
                'observacao' => $dados['observacao'] ?? null,
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | HISTÓRICO
        |--------------------------------------------------------------------------
        */

        if ($item->wasRecentlyCreated) {

            $acao = 'MATERIAL_ADICIONADO';

            $descricao =
                "Adicionou o material {$material->nome}.";

        } else {

            $acao = 'MATERIAL_ATUALIZADO';

            $descricao =
                "Atualizou o material {$material->nome}.";
        }


        HistoricoService::registrar(
            $peca,
            $acao,
            $descricao,
            'peca_materiais',
            $item->id
        );


        return redirect()
            ->route('pecas.show', $peca)
            ->with(
                'sucesso',
                'Material salvo com sucesso.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | TELA DE EDIÇÃO
    |--------------------------------------------------------------------------
    */

    public function edit(
        Peca $peca,
        PecaMaterial $pecaMaterial
    ) {

        /*
        |--------------------------------------------------------------------------
        | SEGURANÇA
        |--------------------------------------------------------------------------
        |
        | Impede acessar um material pertencente a outra peça alterando
        | manualmente o ID na URL.
        |
        */

        if ((int) $pecaMaterial->peca_id !== (int) $peca->id) {
            abort(404);
        }


        $pecaMaterial->load([
            'material',
            'etapa',
        ]);


        return view(
            'pecas.materiais.edit',
            compact(
                'peca',
                'pecaMaterial'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR MATERIAL
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Peca $peca,
        PecaMaterial $pecaMaterial
    ) {

        if ((int) $pecaMaterial->peca_id !== (int) $peca->id) {
            abort(404);
        }


        $dados = $request->validate([
            'quantidade' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'unidade' => [
                'nullable',
                'string',
                'max:30',
            ],

            'observacao' => [
                'nullable',
                'string',
            ],
        ]);


        $pecaMaterial->load('material');


        /*
        |--------------------------------------------------------------------------
        | DESCOBRE O QUE MUDOU
        |--------------------------------------------------------------------------
        */

        $camposAlterados = [];


        $quantidadeAtual =
            $pecaMaterial->quantidade === null
                ? null
                : (float) $pecaMaterial->quantidade;


        $novaQuantidade =
            isset($dados['quantidade'])
                ? (float) $dados['quantidade']
                : null;


        if ($quantidadeAtual !== $novaQuantidade) {
            $camposAlterados[] = 'quantidade';
        }


        if (
            ($pecaMaterial->unidade ?? '') !==
            ($dados['unidade'] ?? '')
        ) {
            $camposAlterados[] = 'unidade';
        }


        if (
            ($pecaMaterial->observacao ?? '') !==
            ($dados['observacao'] ?? '')
        ) {
            $camposAlterados[] = 'observação';
        }


        /*
        |--------------------------------------------------------------------------
        | ATUALIZA
        |--------------------------------------------------------------------------
        */

        $pecaMaterial->update([
            'quantidade' => $dados['quantidade'] ?? null,
            'unidade' => $dados['unidade'] ?? null,
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
                'MATERIAL_EDITADO',
                'Alterou ' .
                    implode(', ', $camposAlterados) .
                    " do material {$pecaMaterial->material->nome}.",
                'peca_materiais',
                $pecaMaterial->id
            );
        }


        return redirect()
            ->route('pecas.show', $peca)
            ->with(
                'sucesso',
                'Material atualizado com sucesso.'
            );
    }
}