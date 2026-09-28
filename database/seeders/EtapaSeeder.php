<?php

namespace Database\Seeders;

use App\Models\Etapa;
use Illuminate\Database\Seeder;

class EtapaSeeder extends Seeder
{
    public function run(): void
    {
        $etapas = [
            [
                'nome' => 'Marcenaria',
                'ordem' => 1,
            ],
            [
                'nome' => 'Preparação',
                'ordem' => 2,
            ],
            [
                'nome' => 'Cola',
                'ordem' => 3,
            ],
            [
                'nome' => 'Estofamento',
                'ordem' => 4,
            ],
        ];

        foreach ($etapas as $etapa) {
            Etapa::updateOrCreate(
                ['nome' => $etapa['nome']],
                [
                    'ordem' => $etapa['ordem'],
                    'ativo' => true,
                ]
            );
        }
    }
}