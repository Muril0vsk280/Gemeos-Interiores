<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pecas', function (Blueprint $table) {
            $table->id();

            $table->string('codigo', 30)->unique();
            $table->string('nome', 150);

            $table->foreignId('tipo_peca_id')
                ->constrained('tipos_peca')
                ->restrictOnDelete();

            $table->text('descricao')->nullable();
            $table->text('observacoes')->nullable();

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pecas');
    }
};