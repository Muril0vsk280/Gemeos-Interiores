<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Historico extends Model
{
    protected $table = 'historico';

    protected $fillable = [
        'user_id',
        'peca_id',
        'acao',
        'tabela',
        'registro_id',
        'descricao',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function peca(): BelongsTo
    {
        return $this->belongsTo(Peca::class);
    }

    public function historicos(): HasMany
    {
    return $this->hasMany(Historico::class)
        ->latest();
    }
}