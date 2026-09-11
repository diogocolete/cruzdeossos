<?php

namespace Database\Seeders;

use App\Models\Conteudo;
use Illuminate\Database\Seeder;

class ConteudoSeeder extends Seeder
{
    public function run(): void
    {
        $conteudos = [
            'sobre_kicker' => 'Quem somos',
            'sobre_titulo' => 'Cruz de Ossos',
            'sobre_lead1' => 'Rode conosco numa jornada pelo desconhecido. Explore novas fronteiras. Acorde pela manhã com a emoção de estar num lugar onde nunca esteve antes.',
            'sobre_lead2' => 'Mantemos o que amamos nas motos — a liberdade, a irmandade e a estrada aberta. Nossa irmandade incentiva o envolvimento da família num ambiente divertido.',

            'por_que_nos_kicker' => 'Nossos benefícios',
            'por_que_nos_titulo' => 'Por Que Nós',

            'evolucao_kicker' => 'Faça parte da família',
            'evolucao_titulo' => 'Evolução na Irmandade',
            'evolucao_subtitulo' => 'Do primeiro passeio ao patch completo — conheça o caminho para se tornar um irmão da Cruz de Ossos.',
            'evolucao_ride_titulo' => 'Roda conosco',
            'evolucao_ride_desc' => 'Rode com a gente para sentir se é isso que você quer. Nesta fase você ainda anda sem colete — é o momento de conhecer a família, a estrada e o nosso jeito de ser.',
            'evolucao_prospect_titulo' => 'Período probatório',
            'evolucao_prospect_desc' => 'Após o período probatório, você recebe a bolacha de metal no peito e a inscrição Prospect nas costas. Agora você representa o clube e prova seu compromisso com a irmandade.',
            'evolucao_full_titulo' => 'Irmão de verdade',
            'evolucao_full_desc' => 'Após adquirir a confiança dos membros do clube, você recebe o patch completo e se torna um irmão de verdade. Agora você é Cruz de Ossos — para sempre.',

            'cta_kicker' => 'Passeio definitivo',
            'cta_titulo' => 'Mantenha a memória viva',
            'cta_btn_texto' => 'Ver Galeria',
            'cta_btn_link' => '#gallery',
            'cta_imagem' => 'banners/banner-5-esboco.png',

            'junte_se_kicker' => 'Faça parte',
            'junte_se_titulo' => 'Junte-se ao clube',
            'junte_se_texto' => 'membros e a família continua crescendo! Inscreva-se para receber novidades, eventos e passeios.',

            'contato_titulo' => 'Tem dúvidas? Não espere, vamos conversar',
            'contato_telefone' => '+55 (41) 99999-9999',
            'contato_email' => 'contato@cruzdeossos.com.br',
            'contato_endereco_titulo' => 'Sede da irmandade',
            'contato_endereco' => 'Curitiba, PR — Brasil',
        ];

        foreach ($conteudos as $chave => $valor) {
            Conteudo::firstOrCreate(['chave' => $chave], ['valor' => $valor]);
        }
    }
}
