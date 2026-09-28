<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Foto extends Model
{
    protected $fillable = [
        'peca_id',
        'etapa_id',
        'caminho',
        'nome_original',
        'descricao',
        'ordem',
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