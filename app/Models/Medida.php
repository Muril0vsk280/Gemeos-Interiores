<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Medida extends Model
{
    protected $fillable = [
        'peca_id',
        'etapa_id',
        'nome',
        'valor',
        'unidade',
        'observacao',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
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