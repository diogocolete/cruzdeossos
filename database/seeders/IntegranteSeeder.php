<?php

namespace Database\Seeders;

use App\Models\Integrante;
use Illuminate\Database\Seeder;

class IntegranteSeeder extends Seeder
{
    public function run(): void
    {
        $integrantes = [
            ['apelido' => 'Barnabé', 'slug' => 'barnabe', 'cargo' => 'Presidente', 'ordem' => 1],
            ['apelido' => 'Colete', 'slug' => 'colete', 'cargo' => 'Secretário', 'ordem' => 2],
            ['apelido' => 'Thiago', 'slug' => 'thiago', 'cargo' => 'Sargente de Armas', 'ordem' => 3],
            ['apelido' => 'Maceno', 'slug' => 'maceno', 'cargo' => 'Membro', 'ordem' => 4],
            ['apelido' => 'Juarez', 'slug' => 'juarez', 'cargo' => 'Membro', 'ordem' => 5],
        ];

        foreach ($integrantes as $data) {
            $integrante = Integrante::firstOrCreate(
                ['apelido' => $data['apelido']],
                $data
            );

            // Garante slug em integrantes já existentes
            if (empty($integrante->slug)) {
                $integrante->update(['slug' => $data['slug']]);
            }
        }
    }
}
