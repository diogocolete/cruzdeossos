<?php

namespace App\Http\Controllers;

use App\Models\Integrante;
use App\Models\Evento;
use App\Models\Noticia;
use App\Models\Galeria;
use App\Models\Depoimento;
use App\Models\Banner;
use App\Models\Beneficio;
use App\Models\Recurso;
use App\Models\EvolucaoPasso;
use App\Models\Configuracao;
use App\Models\Conteudo;
use Illuminate\Http\Request;

class SiteController extends Controller
{
    public function home()
    {
        $banners = Banner::publicados()->get();
        $integrantes = Integrante::ativos()->get();
        $eventos = Evento::ordenados()->get();
        $noticias = Noticia::publicados()->limit(3)->get();
        $galeria = Galeria::publicados()->limit(6)->get();
        $depoimentos = Depoimento::publicados()->get();
        $beneficios = Beneficio::publicados()->get();
        $recursos = Recurso::publicados()->get();
        $evolucaoPassos = EvolucaoPasso::publicados()->get();
        $secoes = Configuracao::secoes();

        $conteudo = Conteudo::muitos([
            'sobre_kicker' => 'Quem somos',
            'sobre_titulo' => 'Cruz de Ossos',
            'sobre_lead1' => '',
            'sobre_lead2' => '',
            'por_que_nos_kicker' => 'Nossos benefícios',
            'por_que_nos_titulo' => 'Por Que Nós',
            'evolucao_kicker' => 'Faça parte da família',
            'evolucao_titulo' => 'Evolução na Irmandade',
            'evolucao_subtitulo' => '',
            'cta_kicker' => 'Passeio definitivo',
            'cta_titulo' => 'Mantenha a memória viva',
            'cta_btn_texto' => 'Ver Galeria',
            'cta_btn_link' => '#gallery',
            'cta_imagem' => 'banners/banner-5-esboco.png',
            'junte_se_kicker' => 'Faça parte',
            'junte_se_titulo' => 'Junte-se ao clube',
            'junte_se_texto' => '',
            'contato_titulo' => 'Tem dúvidas? Não espere, vamos conversar',
            'contato_telefone' => '+55 (41) 99999-9999',
            'contato_email' => 'contato@cruzdeossos.com.br',
            'contato_endereco_titulo' => 'Sede da irmandade',
            'contato_endereco' => 'Curitiba, PR — Brasil',
        ]);

        return view('site.home', compact(
            'banners',
            'integrantes',
            'eventos',
            'noticias',
            'galeria',
            'depoimentos',
            'beneficios',
            'recursos',
            'evolucaoPassos',
            'secoes',
            'conteudo'
        ));
    }
}
