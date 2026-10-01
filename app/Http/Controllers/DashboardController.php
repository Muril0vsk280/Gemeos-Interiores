<?php

namespace App\Http\Controllers;

use App\Models\Peca;
use App\Models\TipoPeca;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | TOTAL DE MODELOS
        |--------------------------------------------------------------------------
        */

        $totalPecas = Peca::count();


        /*
        |--------------------------------------------------------------------------
        | QUANTIDADE POR TIPO
        |--------------------------------------------------------------------------
        */

        $tipos = TipoPeca::where('ativo', true)
            ->withCount('pecas')
            ->orderBy('nome')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | MODELOS CADASTRADOS RECENTEMENTE
        |--------------------------------------------------------------------------
        */

        $pecasRecentes = Peca::with([
            'tipo',
            'criador',
        ])
            ->latest()
            ->take(6)
            ->get();


        return view(
            'dashboard',
            compact(
                'totalPecas',
                'tipos',
                'pecasRecentes'
            )
        );
    }
}