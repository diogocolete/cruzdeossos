<?php

namespace Database\Seeders;

use App\Models\Beneficio;
use Illuminate\Database\Seeder;

class BeneficioSeeder extends Seeder
{
    public function run(): void
    {
        $beneficios = [
            [
                'icone' => '&#9881;',
                'titulo' => 'Herança',
                'descricao' => 'Nossa irmandade está ativa na região, e nossos membros conhecem todas as melhores estradas locais.',
                'publicado' => true,
                'ordem' => 1,
            ],
            [
                'icone' => '&#128752;',
                'titulo' => 'Passeios',
                'descricao' => 'Junte-se a nós em um de nossos muitos passeios durante a temporada. Day rides, escapadas, tours e até viagens internacionais.',
                'publicado' => true,
                'ordem' => 2,
            ],
            [
                'icone' => '&#9749;',
                'titulo' => 'Social',
                'descricao' => 'Realizamos jantares regulares com palestrantes interessantes. Promovemos encontros, cafés e até piqueniques para a família.',
                'publicado' => true,
                'ordem' => 3,
            ],
        ];

        foreach ($beneficios as $data) {
            Beneficio::firstOrCreate(['titulo' => $data['titulo']], $data);
        }
    }
}
