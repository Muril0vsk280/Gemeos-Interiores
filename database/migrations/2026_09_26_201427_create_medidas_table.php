<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medidas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('peca_id')
                ->constrained('pecas')
                ->cascadeOnDelete();

            $table->foreignId('etapa_id')
                ->nullable()
                ->constrained('etapas')
                ->nullOnDelete();

            $table->string('nome', 100);
            $table->decimal('valor', 10, 2)->nullable();
            $table->string('unidade', 20)->default('cm');
            $table->text('observacao')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medidas');
    }
};