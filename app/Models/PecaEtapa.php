<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PecaEtapa extends Model
{
    protected $table = 'peca_etapas';

    protected $fillable = [
        'peca_id',
        'etapa_id',
        'ordem',
        'descricao',
        'observacoes',
    ];

    public function peca(): BelongsTo
    {
        return $this->belongsTo(Peca::class);
    }

    public function etapa(): BelongsTo
    {
        return $this->belongsTo(Etapa::class);
    }
}