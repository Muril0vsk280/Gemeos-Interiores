<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    protected $table = 'materiais';

    protected $fillable = [
        'nome',
        'unidade',
        'descricao',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function pecas(): BelongsToMany
    {
        return $this->belongsToMany(
            Peca::class,
            'peca_materiais'
        )
        ->withPivot([
            'etapa_id',
            'quantidade',
            'unidade',
            'observacao',
        ])
        ->withTimestamps();
    }

    public function pecaMateriais(): HasMany
    {
        return $this->hasMany(PecaMaterial::class);
    }
}