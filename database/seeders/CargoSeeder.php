<?php

namespace Database\Seeders;

use App\Models\Cargo;
use Illuminate\Database\Seeder;

class CargoSeeder extends Seeder
{
    public function run(): void
    {
        $cargos = [
            [
                'nome' => 'Administrador',
                'descricao' => 'Acesso completo ao sistema.',
            ],
            [
                'nome' => 'Encarregado',
                'descricao' => 'Pode consultar, cadastrar e editar informações de produção.',
            ],
            [
                'nome' => 'Atendente',
                'descricao' => 'Acesso voltado principalmente à consulta das informações.',
            ],
            [
                'nome' => 'Funcionário',
                'descricao' => 'Acesso somente para consulta das informações de produção.',
            ],
        ];

        foreach ($cargos as $cargo) {
            Cargo::updateOrCreate(
                ['nome' => $cargo['nome']],
                $cargo
            );
        }
    }
}