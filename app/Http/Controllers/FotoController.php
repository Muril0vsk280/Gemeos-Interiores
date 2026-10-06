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
    /*
    |--------------------------------------------------------------------------
    | ADICIONAR FOTOS
    |--------------------------------------------------------------------------
    */

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


        $etapaId = $dados['etapa_id'] ?? null;


        /*
        |--------------------------------------------------------------------------
        | CONFIRMA SE A ETAPA PERTENCE À PEÇA
        |--------------------------------------------------------------------------
        */

        if ($etapaId) {

            $etapaPertenceAPeca = $peca
                ->etapas()
                ->where(
                    'etapas.id',
                    $etapaId
                )
                ->exists();


            if (!$etapaPertenceAPeca) {

                return back()->withErrors([
                    'fotos' =>
                        'A etapa selecionada não pertence a este modelo.',
                ]);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | PASTA
        |--------------------------------------------------------------------------
        */

        if ($etapaId) {

            $pasta =
                "pecas/{$peca->id}/etapas/{$etapaId}";

        } else {

            $pasta =
                "pecas/{$peca->id}/geral";
        }


        /*
        |--------------------------------------------------------------------------
        | PRÓXIMA ORDEM
        |--------------------------------------------------------------------------
        */

        $ultimaOrdem = Foto::where(
            'peca_id',
            $peca->id
        )
            ->where(
                'etapa_id',
                $etapaId
            )
            ->max('ordem') ?? 0;


        $arquivos =
            $request->file('fotos');


        $quantidadeFotos =
            count($arquivos);


        /*
        |--------------------------------------------------------------------------
        | SALVA
        |--------------------------------------------------------------------------
        */

        foreach ($arquivos as $arquivo) {

            $ultimaOrdem++;


            $caminho =
                $arquivo->store(
                    $pasta,
                    'public'
                );


            Foto::create([
                'peca_id' =>
                    $peca->id,

                'etapa_id' =>
                    $etapaId,

                'caminho' =>
                    $caminho,

                'nome_original' =>
                    $arquivo->getClientOriginalName(),

                'ordem' =>
                    $ultimaOrdem,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | HISTÓRICO
        |--------------------------------------------------------------------------
        */

        if ($etapaId) {

            $etapa =
                Etapa::findOrFail(
                    $etapaId
                );


            $descricaoHistorico =
                "Adicionou {$quantidadeFotos} foto(s) na etapa {$etapa->nome}.";

        } else {

            $descricaoHistorico =
                "Adicionou {$quantidadeFotos} foto(s) gerais ao modelo.";
        }


        HistoricoService::registrar(
            $peca,
            'FOTOS_ADICIONADAS',
            $descricaoHistorico,
            'fotos'
        );


        return redirect()
            ->route(
                'pecas.show',
                $peca
            )
            ->with(
                'sucesso',
                'Fotos adicionadas com sucesso.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | CADASTRAR FOTO PRINCIPAL
    |--------------------------------------------------------------------------
    |
    | Usado quando o modelo ainda não possui nenhuma foto geral.
    |
    */

    public function storePrincipal(
        Request $request,
        Peca $peca
    ) {

        $request->validate([
            'foto' => [
                'required',
                'image',
                'max:10240',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | NÃO PERMITE CRIAR OUTRA PRINCIPAL
        |--------------------------------------------------------------------------
        */

        $jaExiste =
            Foto::where(
                'peca_id',
                $peca->id
            )
                ->whereNull(
                    'etapa_id'
                )
                ->exists();


        if ($jaExiste) {

            return back()->withErrors([
                'foto' =>
                    'Este modelo já possui uma foto principal.',
            ]);
        }


        $arquivo =
            $request->file('foto');


        $caminho =
            $arquivo->store(
                "pecas/{$peca->id}/geral",
                'public'
            );


        $foto = Foto::create([
            'peca_id' =>
                $peca->id,

            'etapa_id' =>
                null,

            'caminho' =>
                $caminho,

            'nome_original' =>
                $arquivo->getClientOriginalName(),

            'ordem' =>
                1,
        ]);


        HistoricoService::registrar(
            $peca,
            'FOTO_PRINCIPAL_ADICIONADA',
            'Adicionou a foto principal do modelo.',
            'fotos',
            $foto->id
        );


        return redirect()
            ->route(
                'pecas.edit',
                $peca
            )
            ->with(
                'sucesso',
                'Foto principal adicionada com sucesso.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | TROCAR FOTO PRINCIPAL
    |--------------------------------------------------------------------------
    */

    public function updatePrincipal(
        Request $request,
        Peca $peca,
        Foto $foto
    ) {

        /*
        |--------------------------------------------------------------------------
        | SEGURANÇA
        |--------------------------------------------------------------------------
        */

        if (
            (int) $foto->peca_id !==
            (int) $peca->id
        ) {
            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | NÃO PERMITE USAR FOTO DE ETAPA
        |--------------------------------------------------------------------------
        */

        if ($foto->etapa_id !== null) {
            abort(404);
        }


        $request->validate([
            'foto' => [
                'required',
                'image',
                'max:10240',
            ],
        ]);


        $arquivo =
            $request->file('foto');


        /*
        |--------------------------------------------------------------------------
        | SALVA NOVA FOTO PRIMEIRO
        |--------------------------------------------------------------------------
        */

        $novoCaminho =
            $arquivo->store(
                "pecas/{$peca->id}/geral",
                'public'
            );


        $caminhoAntigo =
            $foto->caminho;


        /*
        |--------------------------------------------------------------------------
        | ATUALIZA O REGISTRO
        |--------------------------------------------------------------------------
        */

        $foto->update([
            'caminho' =>
                $novoCaminho,

            'nome_original' =>
                $arquivo->getClientOriginalName(),
        ]);


        /*
        |--------------------------------------------------------------------------
        | APAGA ARQUIVO ANTIGO
        |--------------------------------------------------------------------------
        */

        if (
            $caminhoAntigo &&
            Storage::disk('public')
                ->exists($caminhoAntigo)
        ) {

            Storage::disk('public')
                ->delete($caminhoAntigo);
        }


        /*
        |--------------------------------------------------------------------------
        | HISTÓRICO
        |--------------------------------------------------------------------------
        */

        HistoricoService::registrar(
            $peca,
            'FOTO_PRINCIPAL_ATUALIZADA',
            'Alterou a foto principal do modelo.',
            'fotos',
            $foto->id
        );


        return redirect()
            ->route(
                'pecas.edit',
                $peca
            )
            ->with(
                'sucesso',
                'Foto principal atualizada com sucesso.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EXCLUIR FOTO
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Request $request,
        Peca $peca,
        Foto $foto
    ) {

        /*
        |--------------------------------------------------------------------------
        | SEGURANÇA
        |--------------------------------------------------------------------------
        */

        if (
            (int) $foto->peca_id !==
            (int) $peca->id
        ) {
            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | HISTÓRICO
        |--------------------------------------------------------------------------
        */

        if ($foto->etapa_id) {

            $etapa =
                Etapa::find(
                    $foto->etapa_id
                );


            $descricaoHistorico =
                $etapa
                    ? "Removeu uma foto da etapa {$etapa->nome}."
                    : 'Removeu uma foto de uma etapa.';

        } else {

            $descricaoHistorico =
                'Removeu uma foto geral do modelo.';
        }


        $fotoId =
            $foto->id;


        /*
        |--------------------------------------------------------------------------
        | EXCLUI ARQUIVO
        |--------------------------------------------------------------------------
        */

        if (
            $foto->caminho &&
            Storage::disk('public')
                ->exists($foto->caminho)
        ) {

            Storage::disk('public')
                ->delete(
                    $foto->caminho
                );
        }


        /*
        |--------------------------------------------------------------------------
        | EXCLUI BANCO
        |--------------------------------------------------------------------------
        */

        $foto->delete();


        /*
        |--------------------------------------------------------------------------
        | REGISTRA HISTÓRICO
        |--------------------------------------------------------------------------
        */

        HistoricoService::registrar(
            $peca,
            'FOTO_REMOVIDA',
            $descricaoHistorico,
            'fotos',
            $fotoId
        );


        /*
        |--------------------------------------------------------------------------
        | RETORNO
        |--------------------------------------------------------------------------
        |
        | Se a exclusão foi feita na tela de edição,
        | permanece na tela de edição.
        |
        */

        if (
            $request->input('origem') ===
            'edit'
        ) {

            return redirect()
                ->route(
                    'pecas.edit',
                    $peca
                )
                ->with(
                    'sucesso',
                    'Foto removida com sucesso.'
                );
        }


        return redirect()
            ->route(
                'pecas.show',
                $peca
            )
            ->with(
                'sucesso',
                'Foto removida com sucesso.'
            );
    }
}