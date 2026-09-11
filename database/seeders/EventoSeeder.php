<?php

namespace Database\Seeders;

use App\Models\Evento;
use Illuminate\Database\Seeder;

class EventoSeeder extends Seeder
{
    public function run(): void
    {
        $eventos = [
            [
                'data' => '2025-03-22',
                'titulo' => 'Fundação da Irmandade',
                'descricao' => 'A Cruz de Ossos nasceu no dia 22 de março de 2025, fundada por um grupo de amigos apaixonados por motos e pela estrada. O que começou como um pequeno grupo de irmãos cresceu e se tornou uma irmandade.',
                'ordem' => 1,
            ],
            [
                'data' => '2025-05-10',
                'titulo' => 'Primeiros Passeios',
                'descricao' => 'Os primeiros passeios oficiais da irmandade. Definimos nossas cores, nosso patch e o jeito de ser da Cruz de Ossos. A estrada passou a ser nossa casa.',
                'ordem' => 2,
            ],
            [
                'data' => '2025-09-16',
                'titulo' => 'Formação do Quadro',
                'descricao' => 'Com a chegada de novos irmãos, definimos os cargos da irmandade: Presidente, Secretário, Sargente de Armas e membros. A família começava a tomar forma.',
                'ordem' => 3,
            ],
            [
                'data' => '2026-01-15',
                'titulo' => 'Crescimento da Família',
                'descricao' => 'A irmandade continua crescendo. Novos ride, prospects e full patches. A família Cruz de Ossos segue unida pela estrada e pela irmandade.',
                'ordem' => 4,
            ],
        ];

        foreach ($eventos as $data) {
            Evento::firstOrCreate(
                ['titulo' => $data['titulo']],
                $data
            );
        }
    }
}
