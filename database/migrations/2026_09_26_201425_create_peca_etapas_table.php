<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peca_etapas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('peca_id')
                ->constrained('pecas')
                ->cascadeOnDelete();

            $table->foreignId('etapa_id')
                ->constrained('etapas')
                ->restrictOnDelete();

            $table->unsignedInteger('ordem')->default(0);

            $table->timestamps();

            $table->unique(['peca_id', 'etapa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peca_etapas');
    }
};