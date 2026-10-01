<?php

namespace App\Http\Controllers;

use App\Models\Etapa;
use App\Models\Peca;
use App\Models\TipoPeca;
use App\Services\HistoricoService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PecaController extends Controller
{
    public function index(Request $request)
{
    $busca = trim($request->get('busca', ''));
    $tipoId = $request->get('tipo');

    $query = Peca::query()
        ->with([
            'tipo',
            'criador',
        ]);

    /*
    |--------------------------------------------------------------------------
    | PESQUISA POR CÓDIGO OU NOME
    |--------------------------------------------------------------------------
    */

    if ($busca !== '') {

        $query->where(function ($q) use ($busca) {

            $q->where(
                'codigo',
                'like',
                "%{$busca}%"
            )
            ->orWhere(
                'nome',
                'like',
                "%{$busca}%"
            );

        });
    }


    /*
    |--------------------------------------------------------------------------
    | FILTRO POR TIPO
    |--------------------------------------------------------------------------
    */

    if ($tipoId) {

        $query->where(
            'tipo_peca_id',
            $tipoId
        );
    }


    /*
    |--------------------------------------------------------------------------
    | RESULTADOS
    |--------------------------------------------------------------------------
    */

    $pecas = $query
        ->orderBy('nome')
        ->paginate(20)
        ->withQueryString();


    /*
    |--------------------------------------------------------------------------
    | TIPOS PARA O FILTRO
    |--------------------------------------------------------------------------
    */

    $tipos = TipoPeca::where('ativo', true)
        ->orderBy('nome')
        ->get();


    return view(
        'pecas.index',
        compact(
            'pecas',
            'tipos',
            'busca',
            'tipoId'
        )
    );
}
public function create()
{
    $tipos = TipoPeca::where('ativo', true)
        ->orderBy('nome')
        ->get();

    return view('pecas.create', compact('tipos'));
}

public function destroy(
    Request $request,
    Peca $peca
) {
    /*
    |--------------------------------------------------------------------------
    | CONFIRMAÇÃO
    |--------------------------------------------------------------------------
    */

    $request->validate([
        'confirmacao_codigo' => [
            'required',
            'string',
        ],
    ]);


    if (
        trim($request->confirmacao_codigo)
        !==
        $peca->codigo
    ) {

        return back()
            ->withErrors([
                'confirmacao_codigo' =>
                    'O código digitado não corresponde ao modelo.',
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | GUARDA DADOS ANTES DA EXCLUSÃO
    |--------------------------------------------------------------------------
    */

    $pecaId = $peca->id;
    $codigo = $peca->codigo;
    $nome = $peca->nome;

    $usuarioId = auth()->id();
    $usuarioNome = auth()->user()->name;


    /*
    |--------------------------------------------------------------------------
    | EXCLUI DADOS DO BANCO
    |--------------------------------------------------------------------------
    */

    DB::transaction(function () use ($pecaId) {

        /*
        | Materiais vinculados
        */

        DB::table('peca_materiais')
            ->where('peca_id', $pecaId)
            ->delete();


        /*
        | Medidas
        */

        DB::table('medidas')
            ->where('peca_id', $pecaId)
            ->delete();


        /*
        | Fotos - registros do banco
        */

        DB::table('fotos')
            ->where('peca_id', $pecaId)
            ->delete();


        /*
        | Etapas vinculadas
        */

        DB::table('peca_etapas')
            ->where('peca_id', $pecaId)
            ->delete();


        /*
        | Histórico da peça
        */

        DB::table('historico')
            ->where('peca_id', $pecaId)
            ->delete();


        /*
        | Finalmente, o modelo
        */

        DB::table('pecas')
            ->where('id', $pecaId)
            ->delete();
    });


    /*
    |--------------------------------------------------------------------------
    | REMOVE AS FOTOS FÍSICAS
    |--------------------------------------------------------------------------
    */

    $pastaFotos = "pecas/{$pecaId}";

    if (
        Storage::disk('public')
            ->exists($pastaFotos)
    ) {

        Storage::disk('public')
            ->deleteDirectory($pastaFotos);
    }


    /*
    |--------------------------------------------------------------------------
    | REGISTRO DE SEGURANÇA
    |--------------------------------------------------------------------------
    |
    | Como o histórico da própria peça é apagado junto,
    | registramos a exclusão no log do Laravel.
    |
    */

    Log::warning(
        'Modelo excluído permanentemente.',
        [
            'peca_id' => $pecaId,
            'codigo' => $codigo,
            'nome' => $nome,

            'usuario_id' => $usuarioId,
            'usuario' => $usuarioNome,
        ]
    );


    return redirect()
        ->route('pecas.index')
        ->with(
            'sucesso',
            "Modelo {$codigo} - {$nome} excluído permanentemente."
        );
}

public function edit(Peca $peca)
{
    $tipos = TipoPeca::where('ativo', true)
        ->orderBy('nome')
        ->get();

    return view(
        'pecas.edit',
        compact(
            'peca',
            'tipos'
        )
    );
}

public function update(Request $request, Peca $peca)
{
    $dados = $request->validate([
        'codigo' => [
            'required',
            'string',
            'max:30',

            Rule::unique(
                'pecas',
                'codigo'
            )->ignore($peca->id),
        ],

        'nome' => [
            'required',
            'string',
            'max:150',
        ],

        'tipo_peca_id' => [
            'required',
            'exists:tipos_peca,id',
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


    /*
    |--------------------------------------------------------------------------
    | Descobre o que foi alterado
    |--------------------------------------------------------------------------
    */

    $camposAlterados = [];

    if ($peca->codigo !== $dados['codigo']) {
        $camposAlterados[] = 'código';
    }

    if ($peca->nome !== $dados['nome']) {
        $camposAlterados[] = 'nome';
    }

    if ((int) $peca->tipo_peca_id !== (int) $dados['tipo_peca_id']) {
        $camposAlterados[] = 'tipo';
    }

    if (($peca->descricao ?? '') !== ($dados['descricao'] ?? '')) {
        $camposAlterados[] = 'descrição';
    }

    if (($peca->observacoes ?? '') !== ($dados['observacoes'] ?? '')) {
        $camposAlterados[] = 'observações';
    }


    /*
    |--------------------------------------------------------------------------
    | Atualiza
    |--------------------------------------------------------------------------
    */

    $peca->update($dados);


    /*
    |--------------------------------------------------------------------------
    | Histórico
    |--------------------------------------------------------------------------
    */

    if (count($camposAlterados) > 0) {

        $descricaoHistorico =
            'Alterou: ' .
            implode(', ', $camposAlterados) .
            '.';

        HistoricoService::registrar(
            $peca,
            'MODELO_EDITADO',
            $descricaoHistorico,
            'pecas',
            $peca->id
        );
    }


    return redirect()
        ->route('pecas.show', $peca)
        ->with(
            'sucesso',
            'Modelo atualizado com sucesso.'
        );
}

public function store(Request $request)
{
    $dados = $request->validate([
        'codigo' => [
            'required',
            'string',
            'max:30',
            'unique:pecas,codigo',
        ],

        'nome' => [
            'required',
            'string',
            'max:150',
        ],

        'tipo_peca_id' => [
            'required',
            'exists:tipos_peca,id',
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

    $dados['created_by'] = auth()->id();

    $peca = Peca::create($dados);
    HistoricoService::registrar(
         $peca,
        'MODELO_CRIADO',
      "Criou o modelo {$peca->codigo} - {$peca->nome}.",
      'pecas',
      $peca->id
);
    return redirect()
        ->route('pecas.show', $peca)
        ->with('sucesso', 'Modelo cadastrado com sucesso.');
}
  public function show(Peca $peca)
{
    $peca->load([
        'tipo',
        'criador',
        'medidas',
        'fotos',
        'etapas',
        'pecaMateriais.material',
        'pecaMateriais.etapa',
        'historicos.usuario',
    ]);

    $etapasDisponiveis = Etapa::where('ativo', true)
        ->whereNotIn(
            'id',
            $peca->etapas->pluck('id')
        )
        ->orderBy('ordem')
        ->get();

    return view(
        'pecas.show',
        compact(
            'peca',
            'etapasDisponiveis'
        )
    );
}
}