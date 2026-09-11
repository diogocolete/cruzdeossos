<?php

namespace Database\Seeders;

use App\Models\Depoimento;
use Illuminate\Database\Seeder;

class DepoimentoSeeder extends Seeder
{
    public function run(): void
    {
        $depoimentos = [
            [
                'autor' => 'Alex Bold',
                'cargo' => 'Membro',
                'texto' => 'Conhecemos pessoas realmente incríveis que você não necessariamente esperaria conhecer no seu dia a dia. A Cruz de Ossos me deu uma visão incrível das diversas formas de pensar.',
                'ordem' => 1,
            ],
            [
                'autor' => 'John Doe',
                'cargo' => 'Membro',
                'texto' => 'Definitivamente vale o investimento. Belo trabalho na nossa irmandade. Provavelmente eu poderia até entrar em vendas para vocês.',
                'ordem' => 2,
            ],
            [
                'autor' => 'Maria Santos',
                'cargo' => 'Membro',
                'texto' => 'A irmandade da Cruz de Ossos é real. Na estrada ou fora dela, sei que posso contar com cada um dos meus irmãos. Isso não tem preço.',
                'ordem' => 3,
            ],
        ];

        foreach ($depoimentos as $data) {
            Depoimento::firstOrCreate(
                ['autor' => $data['autor']],
                $data
            );
        }
    }
}
