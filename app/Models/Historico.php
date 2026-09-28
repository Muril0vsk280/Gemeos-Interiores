<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Historico extends Model
{
    protected $table = 'historico';

    protected $fillable = [
        'user_id',
        'acao',
        'tabela',
        'registro_id',
        'descricao',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}