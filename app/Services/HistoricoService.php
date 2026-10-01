<?php

namespace App\Services;

use App\Models\Historico;
use App\Models\Peca;
use Illuminate\Support\Facades\Auth;

class HistoricoService
{
    public static function registrar(
        Peca $peca,
        string $acao,
        string $descricao,
        ?string $tabela = null,
        ?int $registroId = null
    ): void {
        Historico::create([
            'user_id' => Auth::id(),
            'peca_id' => $peca->id,
            'acao' => $acao,
            'tabela' => $tabela,
            'registro_id' => $registroId,
            'descricao' => $descricao,
        ]);
    }
}