<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PecaMaterial extends Model
{
    protected $table = 'peca_materiais';

    protected $fillable = [
        'peca_id',
        'material_id',
        'etapa_id',
        'quantidade',
        'unidade',
        'observacao',
    ];

    protected $casts = [
        'quantidade' => 'decimal:2',
    ];

    public function peca(): BelongsTo
    {
        return $this->belongsTo(Peca::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function etapa(): BelongsTo
    {
        return $this->belongsTo(Etapa::class);
    }
}