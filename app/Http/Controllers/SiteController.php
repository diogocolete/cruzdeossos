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
        $galeria = Galeria::destaques()->limit(6)->get();
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

    public function galeria(Request $request)
    {
        if (!Configuracao::secaoAtiva('sec_galeria')) {
            return redirect()->route('home');
        }

        $pastaAtual = $request->query('pasta');

        $query = Galeria::publicados();
        if ($pastaAtual) {
            $query->daPasta($pastaAtual);
        }

        $galeria = $query->paginate(24)->withQueryString();
        $pastas = Galeria::pastas(apenasPublicados: true);
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

        return view('site.galeria', compact('galeria', 'secoes', 'conteudo', 'pastas', 'pastaAtual'));
    }

    public function nossaSede()
    {
        if (!Configuracao::secaoAtiva('sec_sede')) {
            return redirect()->route('home');
        }

        $secoes = Configuracao::secoes();
        $conteudo = $this->conteudoInterno([
            'sede_kicker' => 'Onde nos encontramos',
            'sede_titulo' => 'Nossa Sede',
            'sede_texto' => '',
            'sede_imagem' => '',
            'sede_maps_embed' => '',
            'sede_pasta' => '',
        ]);

        $fotosSede = $conteudo['sede_pasta']
            ? Galeria::publicados()->daPasta($conteudo['sede_pasta'])->get()
            : collect();

        return view('site.nossa-sede', compact('secoes', 'conteudo', 'fotosSede'));
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

    public function noticiaShow(string $slug)
    {
        $noticia = Noticia::publicados()->with('user')->where('slug', $slug)->firstOrFail();
        $secoes = Configuracao::secoes();
        $conteudo = $this->conteudoInterno();
        $outrasNoticias = Noticia::publicados()
            ->where('id', '!=', $noticia->id)
            ->limit(3)
            ->get();

        return view('site.noticias.show', compact('noticia', 'secoes', 'conteudo', 'outrasNoticias'));
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

    public function junteSe()
    {
        if (!Configuracao::secaoAtiva('sec_junte_se')) {
            return redirect()->route('home');
        }

        $secoes = Configuracao::secoes();
        $evolucaoPassos = EvolucaoPasso::publicados()->get();
        $conteudo = $this->conteudoInterno([
            'junte_se_kicker' => 'Faça parte',
            'junte_se_titulo' => 'Junte-se ao clube',
            'junte_se_texto' => '',
            'junte_se_imagem' => 'junte-se/junte-se-moto.jpg',
            'junte_se_btn' => 'Enviar inscrição',
            'evolucao_kicker' => 'Faça parte da família',
            'evolucao_titulo' => 'Evolução na Irmandade',
        ]);

        return view('site.junte-se', compact('secoes', 'conteudo', 'evolucaoPassos'));
    }

    public function inscricao(Request $request)
    {
        // Honeypot: bots preenchem; humanos nunca veem o campo.
        // Nome propositalmente sem significado para o autofill do navegador
        // nao preencher (o antigo "website" era preenchido pelo Chrome).
        if ($request->filled('hp_field')) {
            \Illuminate\Support\Facades\Log::info('Inscricao descartada pelo honeypot', $request->except(['hp_field', '_token']));
            return redirect()->route('junte-se')
                ->with('inscricao_ok', 'Inscrição recebida! Entraremos em contato.');
        }

        $request->validate([
            'nome_completo' => ['required', 'string', 'max:200'],
            'rede_social'   => ['nullable', 'string', 'max:150'],
            'email'         => ['nullable', 'email', 'max:150'],
            'telefone'      => ['nullable', 'string', 'max:30'],
            'whatsapp'      => ['nullable', 'string', 'max:30'],
            'endereco'      => ['nullable', 'string', 'max:250'],
        ]);

        $inscricao = \App\Models\Inscricao::create(array_merge(
            $request->only([
                'nome_completo', 'rede_social', 'email', 'telefone', 'whatsapp', 'endereco',
            ]),
            ['status' => 'analise_basico']
        ));

        return redirect()->route('junte-se.etapa2', $inscricao->token);
    }

    public function junteSeEtapa2(string $token)
    {
        $inscricao = \App\Models\Inscricao::where('token', $token)->firstOrFail();
        $secoes = Configuracao::secoes();
        $conteudo = $this->conteudoInterno();

        return view('site.junte-se-etapa2', compact('inscricao', 'secoes', 'conteudo'));
    }

    public function inscricaoEtapa2(Request $request, string $token)
    {
        $inscricao = \App\Models\Inscricao::where('token', $token)->firstOrFail();

        if ($request->filled('hp_field')) {
            return redirect()->route('home');
        }

        $request->validate([
            'nome_completo'       => ['required', 'string', 'max:200'],
            'endereco'            => ['required', 'string', 'max:250'],
            'cpf'                 => ['required', 'string', 'max:14'],
            'rg'                  => ['required', 'string', 'max:20'],
            'data_nascimento'     => ['required', 'date', 'before:today'],
            'moto'                => ['required', 'string', 'max:150'],
            'ja_pertenceu_clube'  => ['required', 'in:0,1'],
            'clube_anterior'      => ['nullable', 'string', 'max:150', 'required_if:ja_pertenceu_clube,1'],
        ]);

        $inscricao->update(array_merge(
            $request->only([
                'nome_completo', 'endereco', 'cpf', 'rg',
                'data_nascimento', 'moto', 'clube_anterior',
            ]),
            [
                'ja_pertenceu_clube' => $request->input('ja_pertenceu_clube') === '1',
                'clube_anterior' => $request->input('ja_pertenceu_clube') === '1' ? $request->input('clube_anterior') : null,
                'status' => 'analise_completo',
            ]
        ));

        return view('site.junte-se-sucesso', [
            'secoes' => Configuracao::secoes(),
            'conteudo' => $this->conteudoInterno(),
            'inscricao' => $inscricao,
        ]);
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
