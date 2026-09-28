<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fotos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('peca_id')
                ->constrained('pecas')
                ->cascadeOnDelete();

            $table->foreignId('etapa_id')
                ->nullable()
                ->constrained('etapas')
                ->nullOnDelete();

            $table->string('caminho', 500);
            $table->string('nome_original', 255)->nullable();
            $table->text('descricao')->nullable();
            $table->unsignedInteger('ordem')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fotos');
    }
};