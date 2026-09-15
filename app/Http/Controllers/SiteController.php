<?php

namespace App\Http\Controllers;

use App\Models\Integrante;
use App\Models\Evento;
use App\Models\Noticia;
use App\Models\Galeria;
use App\Models\Depoimento;
use App\Models\Banner;
use App\Models\Beneficio;
use App\Models\Post;
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
        $postsDestaque = Post::paraHome(6);
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
            'cta_btn_link' => '/galeria',
            'cta_imagem' => 'banners/banner-5-esboco.png',
            'junte_se_kicker' => 'Faça parte',
            'junte_se_titulo' => 'Junte-se ao clube',
            'junte_se_texto' => '',
            'contato_titulo' => 'Tem dúvidas? Não espere, vamos conversar',
            'contato_telefone' => '+55 (41) 99999-9999',
            'contato_email' => 'contato@cruzdeossos.com.br',
            'contato_endereco_titulo' => 'Sede da irmandade',
            'contato_endereco' => 'Curitiba, PR — Brasil',
            'social_facebook' => '',
            'social_instagram' => '',
            'social_twitter' => '',
            'social_youtube' => '',
            'footer_descricao' => 'Cavaleiros da estrada, irmãos da cruz. Irmandade desde 22/03/2025.',
            'footer_telefone' => '+55 (41) 99999-9999',
            'footer_email' => 'contato@cruzdeossos.com.br',
            'footer_endereco' => 'Curitiba, PR',
        ]);

        return view('site.home', compact(
            'banners',
            'integrantes',
            'eventos',
            'noticias',
            'postsDestaque',
            'galeria',
            'depoimentos',
            'beneficios',
            'recursos',
            'evolucaoPassos',
            'secoes',
            'conteudo'
        ));
    }

    public function galeria()
    {
        if (!Configuracao::secaoAtiva('sec_galeria')) {
            return redirect()->route('home');
        }

        $galeria = Galeria::publicados()->get();
        $secoes = Configuracao::secoes();

        $conteudo = Conteudo::muitos([
            'galeria_kicker' => 'Momentos',
            'galeria_titulo' => 'Galeria da Irmandade',
            'galeria_subtitulo' => 'Cada foto conta um pedaço da nossa história. Passeios, reuniões e momentos de irmandade.',
            'social_facebook' => '',
            'social_instagram' => '',
            'social_twitter' => '',
            'social_youtube' => '',
            'footer_descricao' => 'Cavaleiros da estrada, irmãos da cruz. Irmandade desde 22/03/2025.',
            'footer_telefone' => '+55 (41) 99999-9999',
            'footer_email' => 'contato@cruzdeossos.com.br',
            'footer_endereco' => 'Curitiba, PR',
        ]);

        return view('site.galeria', compact('galeria', 'secoes', 'conteudo'));
    }

    /**
     * Chaves de conteúdo comuns às páginas públicas internas.
     */
    private function conteudoInterno(array $extras = []): array
    {
        return Conteudo::muitos(array_merge([
            'social_facebook' => '',
            'social_instagram' => '',
            'social_twitter' => '',
            'social_youtube' => '',
            'footer_descricao' => 'Cavaleiros da estrada, irmãos da cruz. Irmandade desde 22/03/2025.',
            'footer_telefone' => '+55 (41) 99999-9999',
            'footer_email' => 'contato@cruzdeossos.com.br',
            'footer_endereco' => 'Curitiba, PR',
        ], $extras));
    }

    public function posts()
    {
        $posts = Post::publicados()->with('integrante')->paginate(9);
        $secoes = Configuracao::secoes();
        $conteudo = $this->conteudoInterno([
            'posts_kicker' => 'Blog da irmandade',
            'posts_titulo' => 'Posts',
            'posts_subtitulo' => 'Histórias, passeios e novidades escritos pelos próprios irmãos.',
        ]);

        return view('site.posts.index', compact('posts', 'secoes', 'conteudo'));
    }

    public function postShow(string $slug)
    {
        $post = Post::publicados()->with('integrante')->where('slug', $slug)->firstOrFail();
        $secoes = Configuracao::secoes();
        $conteudo = $this->conteudoInterno();
        $outrosPosts = Post::publicados()
            ->where('id', '!=', $post->id)
            ->limit(3)
            ->get();

        return view('site.posts.show', compact('post', 'secoes', 'conteudo', 'outrosPosts'));
    }

    public function integranteShow(string $slug)
    {
        $integrante = Integrante::ativos()
            ->where('slug', $slug)
            ->first();

        // Fallback: slug gerado a partir do apelido (integrantes sem slug definido)
        $integrante ??= Integrante::ativos()->get()
            ->first(fn ($i) => $i->slugPublico() === $slug);

        abort_unless($integrante, 404);

        $posts = $integrante->posts()->publicados()->get();
        $secoes = Configuracao::secoes();
        $conteudo = $this->conteudoInterno();

        return view('site.integrantes.show', compact('integrante', 'posts', 'secoes', 'conteudo'));
    }
}
