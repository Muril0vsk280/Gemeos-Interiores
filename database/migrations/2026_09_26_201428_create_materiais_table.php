<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materiais', function (Blueprint $table) {
            $table->id();

            $table->string('nome', 150)->unique();
            $table->string('unidade', 30)->nullable();
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materiais');
    }
};