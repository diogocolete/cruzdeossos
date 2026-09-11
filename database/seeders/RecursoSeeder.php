<?php

namespace Database\Seeders;

use App\Models\Recurso;
use Illuminate\Database\Seeder;

class RecursoSeeder extends Seeder
{
    public function run(): void
    {
        $recursos = [
            [
                'icone' => '&#9876;',
                'titulo' => 'Suporte',
                'descricao' => 'Estabeleça uma irmandade de outros motoqueiros de mente semelhante que fazem um juramento de ficar conosco, para apoiar todos os membros.',
                'publicado' => true,
                'ordem' => 1,
            ],
            [
                'icone' => '&#9873;',
                'titulo' => 'Comunidade',
                'descricao' => 'Proporcione a oportunidade de retribuir às comunidades, bem como prestar assistência a veteranos e feridos relacionados.',
                'publicado' => true,
                'ordem' => 2,
            ],
        ];

        foreach ($recursos as $data) {
            Recurso::firstOrCreate(['titulo' => $data['titulo']], $data);
        }
    }
}
