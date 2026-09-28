<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Peca extends Model
{
    protected $table = 'pecas';

    protected $fillable = [
        'codigo',
        'nome',
        'tipo_peca_id',
        'descricao',
        'observacoes',
        'created_by',
    ];

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(TipoPeca::class, 'tipo_peca_id');
    }

    public function criador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function fotos(): HasMany
    {
        return $this->hasMany(Foto::class);
    }

    public function medidas(): HasMany
    {
        return $this->hasMany(Medida::class);
    }

    public function pecaEtapas(): HasMany
    {
        return $this->hasMany(PecaEtapa::class);
    }

    public function etapas(): BelongsToMany
    {
        return $this->belongsToMany(
            Etapa::class,
            'peca_etapas'
        )
        ->withPivot([
            'ordem',
            'descricao',
            'observacoes',
        ])
        ->withTimestamps();
    }

    public function materiais(): BelongsToMany
    {
        return $this->belongsToMany(
            Material::class,
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