<?php

namespace App\Http\Controllers;

use App\Models\Etapa;
use App\Models\Foto;
use App\Models\Peca;
use App\Services\HistoricoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FotoController extends Controller
{
    public function store(Request $request, Peca $peca)
    {
        $dados = $request->validate([
            'etapa_id' => [
                'nullable',
                'exists:etapas,id',
            ],

            'fotos' => [
                'required',
                'array',
                'min:1',
                'max:20',
            ],

            'fotos.*' => [
                'required',
                'image',
                'max:10240',
            ],
        ]);
        
        
        /*
        |--------------------------------------------------------------------------
        | Etapa da foto
        |--------------------------------------------------------------------------
        */

        $etapaId = $dados['etapa_id'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | Confirma se a etapa realmente pertence à peça
        |--------------------------------------------------------------------------
        */

        if ($etapaId) {
            $etapaPertenceAPeca = $peca->etapas()
                ->where('etapas.id', $etapaId)
                ->exists();

            if (!$etapaPertenceAPeca) {
                return back()->withErrors([
                    'fotos' => 'A etapa selecionada não pertence a este modelo.',
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Define onde as imagens serão armazenadas
        |--------------------------------------------------------------------------
        */

        if ($etapaId) {
            $pasta = "pecas/{$peca->id}/etapas/{$etapaId}";
        } else {
            $pasta = "pecas/{$peca->id}/geral";
        }

        /*
        |--------------------------------------------------------------------------
        | Descobre a próxima posição das fotos
        |--------------------------------------------------------------------------
        */

        $ultimaOrdem = Foto::where('peca_id', $peca->id)
            ->where('etapa_id', $etapaId)
            ->max('ordem') ?? 0;

        /*
        |--------------------------------------------------------------------------
        | Arquivos enviados
        |--------------------------------------------------------------------------
        */

        $arquivos = $request->file('fotos');

        $quantidadeFotos = count($arquivos);

        /*
        |--------------------------------------------------------------------------
        | Salva todas as fotos
        |--------------------------------------------------------------------------
        */

        foreach ($arquivos as $arquivo) {
            $ultimaOrdem++;

            $caminho = $arquivo->store(
                $pasta,
                'public'
            );

            Foto::create([
                'peca_id' => $peca->id,
                'etapa_id' => $etapaId,
                'caminho' => $caminho,
                'nome_original' => $arquivo->getClientOriginalName(),
                'ordem' => $ultimaOrdem,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Monta a descrição do histórico
        |--------------------------------------------------------------------------
        */

        if ($etapaId) {
            $etapa = Etapa::findOrFail($etapaId);

            $descricaoHistorico =
                "Adicionou {$quantidadeFotos} foto(s) na etapa {$etapa->nome}.";
        } else {
            $descricaoHistorico =
                "Adicionou {$quantidadeFotos} foto(s) gerais ao modelo.";
        }

        /*
        |--------------------------------------------------------------------------
        | Registra no histórico
        |--------------------------------------------------------------------------
        */

        HistoricoService::registrar(
            $peca,
            'FOTOS_ADICIONADAS',
            $descricaoHistorico,
            'fotos'
        );

        return redirect()
            ->route('pecas.show', $peca)
            ->with(
                'sucesso',
                'Fotos adicionadas com sucesso.'
            );
    }

    /*
|--------------------------------------------------------------------------
| EXCLUIR FOTO
|--------------------------------------------------------------------------
*/

public function destroy(Peca $peca, Foto $foto)
{
    /*
    |--------------------------------------------------------------------------
    | SEGURANÇA
    |--------------------------------------------------------------------------
    |
    | Impede excluir uma foto de outra peça alterando o ID na URL.
    |
    */

    if ((int) $foto->peca_id !== (int) $peca->id) {
        abort(404);
    }


    /*
    |--------------------------------------------------------------------------
    | INFORMAÇÕES PARA O HISTÓRICO
    |--------------------------------------------------------------------------
    */

    if ($foto->etapa_id) {

        $etapa = Etapa::find($foto->etapa_id);

        $descricaoHistorico = $etapa
            ? "Removeu uma foto da etapa {$etapa->nome}."
            : 'Removeu uma foto de uma etapa.';

    } else {

        $descricaoHistorico =
            'Removeu uma foto geral do modelo.';
    }


    /*
    |--------------------------------------------------------------------------
    | GUARDA ID ANTES DE EXCLUIR
    |--------------------------------------------------------------------------
    */

    $fotoId = $foto->id;


    /*
    |--------------------------------------------------------------------------
    | EXCLUI O ARQUIVO DO STORAGE
    |--------------------------------------------------------------------------
    */

    if (
        $foto->caminho &&
        Storage::disk('public')->exists($foto->caminho)
    ) {
        Storage::disk('public')->delete(
            $foto->caminho
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EXCLUI DO BANCO
    |--------------------------------------------------------------------------
    */

    $foto->delete();


    /*
    |--------------------------------------------------------------------------
    | HISTÓRICO
    |--------------------------------------------------------------------------
    */

    HistoricoService::registrar(
        $peca,
        'FOTO_REMOVIDA',
        $descricaoHistorico,
        'fotos',
        $fotoId
    );


    return redirect()
        ->route('pecas.show', $peca)
        ->with(
            'sucesso',
            'Foto removida com sucesso.'
        );
}
}