<?php

namespace Database\Seeders;

use App\Models\Integrante;
use Illuminate\Database\Seeder;

class IntegranteSeeder extends Seeder
{
    public function run(): void
    {
        $integrantes = [
            ['apelido' => 'Barnabé', 'cargo' => 'Presidente', 'ordem' => 1],
            ['apelido' => 'Colete', 'cargo' => 'Secretário', 'ordem' => 2],
            ['apelido' => 'Thiago', 'cargo' => 'Sargente de Armas', 'ordem' => 3],
            ['apelido' => 'Maceno', 'cargo' => 'Membro', 'ordem' => 4],
            ['apelido' => 'Juarez', 'cargo' => 'Membro', 'ordem' => 5],
        ];

        foreach ($integrantes as $data) {
            Integrante::firstOrCreate(
                ['apelido' => $data['apelido']],
                $data
            );
        }
    }
}
