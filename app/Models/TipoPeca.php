<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoPeca extends Model
{
    protected $table = 'tipos_peca';

    protected $fillable = [
        'nome',
        'descricao',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function pecas(): HasMany
    {
        return $this->hasMany(Peca::class);
    }
}