@extends('site.layout')

@section('title', 'Cruz de Ossos — Irmandade')

@section('content')

  <!-- ===== TOP BAR ===== -->
  @include('site.partials.topbar')

  <!-- ===== HEADER ===== -->
  <header class="header" id="header">
    <div class="container header__inner">
      <a href="#home" class="header__logo">
        <img src="{{ asset('assets/logo-vetorial.png') }}" alt="Cruz de Ossos" />
      </a>
      <nav class="nav" id="nav">
        <button class="nav__close" id="navClose" aria-label="Fechar menu">&times;</button>
        <ul class="nav__list">
          <li><a href="#home" class="active">Início</a></li>
          @if($secoes['sec_sobre'] ?? true)<li><a href="#about">O Clube</a></li>@endif
          @if($secoes['sec_eventos'] ?? true)<li><a href="#events">Eventos</a></li>@endif
          @if($secoes['sec_por_que_nos'] ?? true)<li><a href="#why">Por Que Nós</a></li>@endif
          @if($secoes['sec_evolucao'] ?? true)<li><a href="#evolution">Evolução</a></li>@endif
          @if($secoes['sec_integrantes'] ?? true)<li><a href="#crew">Integrantes</a></li>@endif
          @if($secoes['sec_galeria'] ?? true)<li><a href="{{ route('galeria') }}">Galeria</a></li>@endif
          @if($secoes['sec_noticias'] ?? true)<li><a href="#news">Notícias</a></li>@endif
          @if($secoes['sec_posts'] ?? true)<li><a href="#posts">Posts</a></li>@endif
          @if($secoes['sec_contato'] ?? true)<li><a href="#contact">Contato</a></li>@endif
        </ul>
      </nav>
      @if($secoes['sec_junte_se'] ?? true)<a href="{{ route('junte-se') }}" class="btn btn--primary header__cta">Junte-se ao Clube</a>@endif
      <button class="nav__toggle" id="navToggle" aria-label="Abrir menu">
        <span></span><span></span><span></span>
      </button>
    </div>
  </header>

  <!-- ===== HERO SLIDER ===== -->
  <section class="hero" id="home">
    <div class="hero__slides" id="heroSlides">
      @php($i = 0)
      @foreach($banners as $banner)
      <div class="hero__slide{{ $i === 0 ? ' active' : '' }}" style="background-image:url('{{ asset('storage/' . $banner->imagem) }}')">
        <div class="hero__overlay"></div>
        <div class="container hero__content">
          @if($banner->kicker)<p class="hero__kicker">{{ $banner->kicker }}</p>@endif
          <h1 class="hero__title">{{ $banner->titulo }}@if($banner->subtitulo)<br /><span>{{ $banner->subtitulo }}</span>@endif</h1>
          @if($banner->texto)<p class="hero__text">{{ $banner->texto }}</p>@endif
          @if($banner->btn1_texto || $banner->btn2_texto)
          <div class="hero__actions">
            @if($banner->btn1_texto)<a href="{{ $banner->btn1_link ?? '#' }}" class="btn btn--primary">{{ $banner->btn1_texto }}</a>@endif
            @if($banner->btn2_texto)<a href="{{ $banner->btn2_link ?? '#' }}" class="btn btn--ghost">{{ $banner->btn2_texto }}</a>@endif
          </div>
          @endif
        </div>
      </div>
      @php($i++)
      @endforeach
    </div>
    <div class="hero__dots" id="heroDots"></div>
    <div class="hero__arrows">
      <button id="heroPrev" aria-label="Anterior">&#10094;</button>
      <button id="heroNext" aria-label="Próximo">&#10095;</button>
    </div>
  </section>

  <!-- ===== ABOUT ===== -->
  @if($secoes['sec_sobre'] ?? true)
  <section class="about" id="about">
    <div class="container about__inner">
      <div class="about__img">
        <img src="{{ asset('assets/vetor-patch-03.png') }}" alt="Patch Cruz de Ossos" />
      </div>
      <div class="about__text">
        <p class="section__kicker">{{ $conteudo['sobre_kicker'] }}</p>
        <h2 class="section__title">{{ $conteudo['sobre_titulo'] }}</h2>
        <p class="about__lead">{{ $conteudo['sobre_lead1'] }}</p>
        <p class="about__lead">{{ $conteudo['sobre_lead2'] }}</p>
        <div class="about__features">
          @foreach($recursos as $recurso)
          <div class="about__feature">
            <div class="about__feature-icon">@svg($recurso->icone ?? 'heroicon-o-heart')</div>
            <h3>{{ $recurso->titulo }}</h3>
            <p>{{ $recurso->descricao }}</p>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>
  @endif

  <!-- ===== EVENTS TIMELINE ===== -->
  @if($secoes['sec_eventos'] ?? true)
  <section class="history" id="events">
    <div class="container">
      <div class="section__head section__head--center">
        <p class="section__kicker">Nossa trajetória</p>
        <h2 class="section__title">Eventos</h2>
      </div>
      <div class="timeline" id="timeline">
        @foreach($eventos as $i => $evento)
        <div class="timeline__item @if($i === 0) active @endif" data-year="{{ $evento->data->format('Y') }}">
          <div class="timeline__dot"></div>
          <div class="timeline__content">
            <span class="timeline__year">{{ $evento->data->format('d/m/Y') }}</span>
            <h3>{{ $evento->titulo }}</h3>
            <p>{{ $evento->descricao }}</p>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  <!-- ===== WHY CHOOSE US ===== -->
  @if($secoes['sec_por_que_nos'] ?? true)
  <section class="why" id="why">
    <div class="container">
      <div class="section__head section__head--center">
        <p class="section__kicker">{{ $conteudo['por_que_nos_kicker'] }}</p>
        <h2 class="section__title">{{ $conteudo['por_que_nos_titulo'] }}</h2>
      </div>
      <div class="why__grid">
        @foreach($beneficios as $beneficio)
        <div class="why__card">
          <div class="why__icon">@svg($beneficio->icone ?? 'heroicon-o-star')</div>
          <h3>{{ $beneficio->titulo }}</h3>
          <p>{{ $beneficio->descricao }}</p>
        </div>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  <!-- ===== EVOLUTION ===== -->
  @if($secoes['sec_evolucao'] ?? true)
  <section class="evolution" id="evolution">
    <div class="container">
      <div class="section__head section__head--center">
        <p class="section__kicker">{{ $conteudo['evolucao_kicker'] }}</p>
        <h2 class="section__title">{{ $conteudo['evolucao_titulo'] }}</h2>
        <p class="section__subtitle">{{ $conteudo['evolucao_subtitulo'] }}</p>
      </div>
      <div class="evolution__track">
        <div class="evolution__line"></div>
        @foreach($evolucaoPassos as $passo)
        <div class="evolution__step">
          <div class="evolution__num">{{ $passo->numero }}</div>
          <div class="evolution__badge{{ $passo->destacado ? ' evolution__badge--full' : '' }}">
            <img src="{{ $passo->imagem ? asset('storage/' . $passo->imagem) : asset('assets/vetor-patch-03.png') }}" alt="{{ $passo->tag }}" />
          </div>
          <div class="evolution__body">
            <span class="evolution__tag{{ $passo->destacado ? ' evolution__tag--full' : '' }}">{{ $passo->tag }}</span>
            <h3>{{ $passo->titulo }}</h3>
            <p>{!! $passo->descricao !!}</p>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  <!-- ===== CTA BANNER ===== -->
  @if($secoes['sec_cta'] ?? true)
  <section class="cta-banner" style="background-image:url('{{ asset('storage/' . $conteudo['cta_imagem']) }}')">
    <div class="cta-banner__overlay"></div>
    <div class="container cta-banner__content">
      <p class="section__kicker">{{ $conteudo['cta_kicker'] }}</p>
      <h2 class="section__title">{{ $conteudo['cta_titulo'] }}</h2>
      <a href="{{ $conteudo['cta_btn_link'] }}" class="btn btn--primary">{{ $conteudo['cta_btn_texto'] }}</a>
    </div>
  </section>
  @endif

  <!-- ===== GALLERY ===== -->
  @if(($secoes['sec_galeria'] ?? true) && $galeria->isNotEmpty())
  <section class="gallery" id="gallery">
    <div class="container">
      <div class="section__head section__head--center">
        <p class="section__kicker">Momentos</p>
        <h2 class="section__title">Galeria</h2>
      </div>
      <div class="gallery__grid">
        @foreach($galeria as $item)
        <div class="gallery__item" data-lightbox="{{ asset('storage/' . $item->imagem) }}" data-title="{{ $item->titulo }}" data-desc="{{ $item->descricao }}">
          <img src="{{ asset('storage/' . $item->imagem) }}" alt="{{ $item->titulo }}" loading="lazy" style="width:100%;height:100%;object-fit:cover;display:block;" />
          <span>{{ $item->titulo }}</span>
        </div>
        @endforeach
      </div>
      <div style="text-align:center;margin-top:36px;">
        <a href="{{ route('galeria') }}" class="btn btn--ghost">Ver todas as fotos</a>
      </div>
    </div>
  </section>
  @endif

  <!-- ===== NEWS ===== -->
  @if($secoes['sec_noticias'] ?? true)
  <section class="news" id="news">
    <div class="container">
      <div class="section__head">
        <div>
          <p class="section__kicker">Últimas notícias</p>
          <h2 class="section__title">Postagens recentes</h2>
        </div>
        <a href="#" class="btn btn--ghost">Ver todas</a>
      </div>
      <div class="news__grid">
        @foreach($noticias as $noticia)
        <article class="news__card">
          <div class="news__img" style="background-image:url('{{ $noticia->imagem ? asset('storage/' . $noticia->imagem) : asset('assets/banner-3-esboco.png') }}')"></div>
          <div class="news__body">
            <div class="news__meta"><span>{{ $noticia->data_publicacao->format('d M Y') }}</span> · <span>{{ $noticia->user->name ?? 'Admin' }}</span></div>
            <h3>{{ $noticia->titulo }}</h3>
            <p>{{ $noticia->resumo }}</p>
            <a href="#" class="news__more">Ler mais &rarr;</a>
          </div>
        </article>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  <!-- ===== POSTS EM DESTAQUE ===== -->
  @if(($secoes['sec_posts'] ?? true) && $postsDestaque->isNotEmpty())
  <section class="news posts-destaque" id="posts">
    <div class="container">
      <div class="section__head">
        <div>
          <p class="section__kicker">Da estrada para o blog</p>
          <h2 class="section__title">Posts em Destaque</h2>
        </div>
        <a href="{{ route('posts.index') }}" class="btn btn--ghost">Ver todos</a>
      </div>
      <div class="news__grid">
        @foreach($postsDestaque as $post)
        <article class="news__card">
          <a href="{{ route('posts.show', $post->slug) }}" class="news__img" style="background-image:url('{{ $post->imagem ? asset('storage/' . $post->imagem) : asset('assets/banner-3-esboco.png') }}')">
            @if($post->destaque)<span class="news__badge">Destaque</span>@endif
          </a>
          <div class="news__body">
            <div class="news__meta">
              <span>{{ ($post->published_at ?? $post->created_at)->format('d M Y') }}</span> ·
              <a href="{{ route('integrantes.show', $post->integrante->slugPublico()) }}" class="news__autor">{{ $post->integrante->apelido }}</a>
            </div>
            <h3><a href="{{ route('posts.show', $post->slug) }}">{{ $post->titulo }}</a></h3>
            <p>{{ str(strip_tags($post->conteudo))->limit(120) }}</p>
            <a href="{{ route('posts.show', $post->slug) }}" class="news__more">Ler mais &rarr;</a>
          </div>
        </article>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  <!-- ===== CREW MEMBERS ===== -->
  @if($secoes['sec_integrantes'] ?? true)
  <section class="crew" id="crew">
    <div class="container">
      <div class="section__head section__head--center">
        <p class="section__kicker">Nossa família</p>
        <h2 class="section__title">Conheça os Integrantes</h2>
        <p class="section__subtitle">Os irmãos que fazem a Cruz de Ossos existir — cada um com seu papel na irmandade.</p>
      </div>
      <div class="crew__grid">
        @foreach($integrantes as $integrante)
        <a href="{{ route('integrantes.show', $integrante->slugPublico()) }}" class="crew__card">
          <div class="crew__photo" style="background-image:url('{{ $integrante->foto ? asset('storage/' . $integrante->foto) : asset('assets/vetor-patch-03.png') }}')"></div>
          <div class="crew__info">
            <div class="crew__patch"><img src="{{ asset('assets/vetor-patch-03.png') }}" alt="Cruz de Ossos" /></div>
            <h3>{{ $integrante->apelido }}</h3>
            <span class="crew__role">{{ $integrante->cargo }}</span>
          </div>
        </a>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  <!-- ===== TESTIMONIALS ===== -->
  @if($secoes['sec_depoimentos'] ?? true)
  <section class="testimonials">
    <div class="container">
      <div class="section__head section__head--center">
        <p class="section__kicker">Depoimentos</p>
        <h2 class="section__title">O que nossos membros dizem</h2>
      </div>
      <div class="testimonials__slider" id="testimonials">
        @php($i = 0)
        @foreach($depoimentos as $depoimento)
        <div class="testimonial @if($i === 0) active @endif">
          <p class="testimonial__quote">&ldquo;{{ $depoimento->texto }}&rdquo;</p>
          <div class="testimonial__author">
            <div class="testimonial__avatar">{{ strtoupper(substr($depoimento->autor, 0, 2)) }}</div>
            <div>
              <strong>{{ $depoimento->autor }}</strong>
              <span>{{ $depoimento->cargo }}</span>
            </div>
          </div>
        </div>
        @php($i++)
        @endforeach
      </div>
      <div class="testimonials__dots" id="testiDots"></div>
    </div>
  </section>
  @endif

  <!-- ===== CONTACT ===== -->
  @if($secoes['sec_contato'] ?? true)
  <section class="contact" id="contact">
    <div class="container">
      <div class="contact__grid">
        <div class="contact__col">
          <h2 class="section__title">{!! nl2br(e($conteudo['contato_titulo'])) !!}</h2>
          <ul class="contact__list">
            <li><span>&#9742;</span> {{ $conteudo['contato_telefone'] }}</li>
            <li><span>&#9993;</span> {{ $conteudo['contato_email'] }}</li>
          </ul>
          <div class="contact__office">
            <h3>{{ $conteudo['contato_endereco_titulo'] }}</h3>
            <p>{{ $conteudo['contato_endereco'] }}</p>
            <a href="#" class="link--arrow">Ver mapa &rarr;</a>
          </div>
        </div>
        <div class="contact__col">
          <form class="contact__form" onsubmit="return false;">
            <div class="form__row">
              <input type="text" placeholder="Nome *" required />
              <input type="email" placeholder="E-mail *" required />
            </div>
            <input type="text" placeholder="Assunto" />
            <textarea placeholder="Mensagem *" required></textarea>
            <label class="form__check">
              <input type="checkbox" /> Concordo que meus dados enviados estão sendo coletados e armazenados.
            </label>
            <button type="submit" class="btn btn--primary">Enviar mensagem</button>
          </form>
        </div>
      </div>
    </div>
  </section>
  @endif

  <!-- ===== FOOTER ===== -->
  @include('site.partials.footer')

@endsection
