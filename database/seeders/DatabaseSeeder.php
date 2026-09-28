<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CargoSeeder::class,
            TipoPecaSeeder::class,
            EtapaSeeder::class,
        ]);
    }
}