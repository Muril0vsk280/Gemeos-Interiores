<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'cargo_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('cargo_id')
                    ->nullable()
                    ->constrained('cargos')
                    ->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('users', 'active')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('active')->default(true);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'active')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('active');
            });
        }

        if (Schema::hasColumn('users', 'cargo_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['cargo_id']);
                $table->dropColumn('cargo_id');
            });
        }
    }
};