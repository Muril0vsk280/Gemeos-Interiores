<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peca_materiais', function (Blueprint $table) {
            $table->id();

            $table->foreignId('peca_id')
                ->constrained('pecas')
                ->cascadeOnDelete();

            $table->foreignId('material_id')
                ->constrained('materiais')
                ->restrictOnDelete();

            $table->foreignId('etapa_id')
                ->nullable()
                ->constrained('etapas')
                ->nullOnDelete();

            $table->decimal('quantidade', 10, 2)->nullable();
            $table->string('unidade', 30)->nullable();
            $table->text('observacao')->nullable();

            $table->timestamps();

            $table->unique(['peca_id', 'material_id', 'etapa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peca_materiais');
    }
};