<?php

namespace App\Http\Controllers;

use App\Models\Medida;
use App\Models\Peca;
use Illuminate\Http\Request;

class MedidaController extends Controller
{public function store(Request $request, Peca $peca)
{
    $dados = $request->validate([
        'medidas' => ['required', 'array'],

        'medidas.*.nome' => [
            'required',
            'string',
            'max:100',
        ],

        'medidas.*.valor' => [
            'nullable',
            'numeric',
            'min:0',
        ],

        'medidas.*.unidade' => [
            'required',
            'string',
            'max:20',
        ],

        'medidas.*.observacao' => [
            'nullable',
            'string',
        ],
    ]);

    foreach ($dados['medidas'] as $medida) {

        // Se não informou valor, não cadastra essa medida.
        if (
            !isset($medida['valor']) ||
            $medida['valor'] === ''
        ) {
            continue;
        }

        Medida::updateOrCreate(
            [
                'peca_id' => $peca->id,
                'etapa_id' => null,
                'nome' => $medida['nome'],
            ],
            [
                'valor' => $medida['valor'],
                'unidade' => $medida['unidade'],
                'observacao' => $medida['observacao'] ?? null,
            ]
        );
    }

    return redirect()
        ->route('pecas.show', $peca)
        ->with('sucesso', 'Medidas salvas com sucesso.');
}
}