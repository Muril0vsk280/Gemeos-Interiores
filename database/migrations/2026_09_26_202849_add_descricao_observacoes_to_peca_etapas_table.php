<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peca_etapas', function (Blueprint $table) {
            $table->text('descricao')->nullable();
            $table->text('observacoes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('peca_etapas', function (Blueprint $table) {
            $table->dropColumn([
                'descricao',
                'observacoes'
            ]);
        });
    }
};