<?php

namespace Database\Seeders;

use App\Models\TipoPeca;
use Illuminate\Database\Seeder;

class TipoPecaSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = [
            'Cadeira',
            'Poltrona',
            'Sofá',
            'Banqueta',
            'Mesa',
        ];

        foreach ($tipos as $tipo) {
            TipoPeca::updateOrCreate(
                ['nome' => $tipo],
                [
                    'ativo' => true,
                ]
            );
        }
    }
}