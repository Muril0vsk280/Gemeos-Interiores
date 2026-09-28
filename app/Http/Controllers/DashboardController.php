<?php

namespace App\Http\Controllers;

use App\Models\Peca;
use App\Models\TipoPeca;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPecas = Peca::count();

        $tipos = TipoPeca::where('ativo', true)
            ->withCount('pecas')
            ->orderBy('nome')
            ->get();

        $pecasRecentes = Peca::with('tipo')
            ->latest()
            ->take(6)
            ->get();

        return view('dashboard', compact(
            'totalPecas',
            'tipos',
            'pecasRecentes'
        ));
    }
}