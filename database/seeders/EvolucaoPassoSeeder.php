<?php

namespace Database\Seeders;

use App\Models\EvolucaoPasso;
use Illuminate\Database\Seeder;

class EvolucaoPassoSeeder extends Seeder
{
    public function run(): void
    {
        $passos = [
            [
                'numero' => '01',
                'tag' => 'Ride',
                'titulo' => 'Roda conosco',
                'descricao' => 'Rode com a gente para sentir se é isso que você quer. Nesta fase você ainda anda sem colete — é o momento de conhecer a família, a estrada e o nosso jeito de ser.',
                'imagem' => 'evolucao/vetor-patch-03.png',
                'destacado' => false,
                'publicado' => true,
                'ordem' => 1,
            ],
            [
                'numero' => '02',
                'tag' => 'Prospect',
                'titulo' => 'Período probatório',
                'descricao' => 'Após o período probatório, você recebe a bolacha de metal no peito e a inscrição <strong>Prospect</strong> nas costas. Agora você representa o clube e prova seu compromisso com a irmandade.',
                'imagem' => 'evolucao/vetor-patch-03.png',
                'destacado' => false,
                'publicado' => true,
                'ordem' => 2,
            ],
            [
                'numero' => '03',
                'tag' => 'Full Patch',
                'titulo' => 'Irmão de verdade',
                'descricao' => 'Após adquirir a confiança dos membros do clube, você recebe o patch completo e se torna um irmão de verdade. Agora você é Cruz de Ossos — para sempre.',
                'imagem' => 'evolucao/vetor-patch-03.png',
                'destacado' => true,
                'publicado' => true,
                'ordem' => 3,
            ],
        ];

        foreach ($passos as $data) {
            EvolucaoPasso::firstOrCreate(['tag' => $data['tag']], $data);
        }
    }
}
