<?php

namespace App\Http\Controllers;

use App\Models\Peca;
use App\Models\TipoPeca;
use Illuminate\Http\Request;

class PecaController extends Controller
{
    public function index(Request $request)
    {
        $busca = trim($request->input('busca', ''));

        $query = Peca::with([
            'tipo',
            'criador'
        ]);

        if ($busca !== '') {
            $query->where(function ($q) use ($busca) {
                $q->where('codigo', 'like', "%{$busca}%")
                  ->orWhere('nome', 'like', "%{$busca}%");
            });
        }

        if ($request->filled('tipo')) {
            $query->where('tipo_peca_id', $request->tipo);
        }

        $pecas = $query
            ->orderBy('nome')
            ->paginate(20)
            ->withQueryString();

        $tipos = TipoPeca::where('ativo', true)
            ->orderBy('nome')
            ->get();

        return view('pecas.index', compact(
            'pecas',
            'tipos',
            'busca'
        ));
    }
public function create()
{
    $tipos = TipoPeca::where('ativo', true)
        ->orderBy('nome')
        ->get();

    return view('pecas.create', compact('tipos'));
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
    ]);

    return view('pecas.show', compact('peca'));
    }
}