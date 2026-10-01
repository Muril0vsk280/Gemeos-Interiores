<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('historico', function (Blueprint $table) {
            $table->foreignId('peca_id')
                ->nullable()
                ->after('user_id')
                ->constrained('pecas')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('historico', function (Blueprint $table) {
            $table->dropForeign(['peca_id']);
            $table->dropColumn('peca_id');
        });
    }
};