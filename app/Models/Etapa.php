<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Etapa extends Model
{
    protected $fillable = [
        'nome',
        'descricao',
        'ordem',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function pecas(): BelongsToMany
    {
        return $this->belongsToMany(
            Peca::class,
            'peca_etapas'
        )
        ->withPivot([
            'ordem',
            'descricao',
            'observacoes',
        ])
        ->withTimestamps();
    }

    public function pecaEtapas(): HasMany
    {
        return $this->hasMany(PecaEtapa::class);
    }

    public function fotos(): HasMany
    {
        return $this->hasMany(Foto::class);
    }

    public function medidas(): HasMany
    {
        return $this->hasMany(Medida::class);
    }

    public function pecaMateriais(): HasMany
    {
        return $this->hasMany(PecaMaterial::class);
    }
}