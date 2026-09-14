@extends('site.layout')

@section('title', 'Galeria — Cruz de Ossos')

@section('content')

  <!-- ===== TOP BAR ===== -->
  @include('site.partials.topbar')

  <!-- ===== HEADER ===== -->
  <header class="header" id="header">
    <div class="container header__inner">
      <a href="{{ route('home') }}" class="header__logo">
        <img src="{{ asset('assets/logo-vetorial.png') }}" alt="Cruz de Ossos" />
      </a>
      <nav class="nav" id="nav">
        <button class="nav__close" id="navClose" aria-label="Fechar menu">&times;</button>
        <ul class="nav__list">
          <li><a href="{{ route('home') }}">Início</a></li>
          @if($secoes['sec_sobre'] ?? true)<li><a href="{{ route('home') }}#about">O Clube</a></li>@endif
          @if($secoes['sec_eventos'] ?? true)<li><a href="{{ route('home') }}#events">Eventos</a></li>@endif
          @if($secoes['sec_por_que_nos'] ?? true)<li><a href="{{ route('home') }}#why">Por Que Nós</a></li>@endif
          @if($secoes['sec_evolucao'] ?? true)<li><a href="{{ route('home') }}#evolution">Evolução</a></li>@endif
          @if($secoes['sec_integrantes'] ?? true)<li><a href="{{ route('home') }}#crew">Integrantes</a></li>@endif
          @if($secoes['sec_galeria'] ?? true)<li><a href="{{ route('galeria') }}" class="active">Galeria</a></li>@endif
          @if($secoes['sec_noticias'] ?? true)<li><a href="{{ route('home') }}#news">Notícias</a></li>@endif
          @if($secoes['sec_contato'] ?? true)<li><a href="{{ route('home') }}#contact">Contato</a></li>@endif
        </ul>
      </nav>
      @if($secoes['sec_junte_se'] ?? true)<a href="{{ route('home') }}#join" class="btn btn--primary header__cta">Junte-se ao Clube</a>@endif
      <button class="nav__toggle" id="navToggle" aria-label="Abrir menu">
        <span></span><span></span><span></span>
      </button>
    </div>
  </header>

  <!-- ===== PAGE HERO ===== -->
  <section class="page-hero">
    <div class="container">
      <p class="section__kicker">{{ $conteudo['galeria_kicker'] ?? 'Momentos' }}</p>
      <h1 class="page-hero__title">{{ $conteudo['galeria_titulo'] ?? 'Galeria da Irmandade' }}</h1>
      <p class="page-hero__subtitle">{{ $conteudo['galeria_subtitulo'] ?? '' }}</p>
    </div>
  </section>

  <!-- ===== GALLERY FULL ===== -->
  <section class="gallery gallery--full">
    <div class="container">
      <div class="gallery__grid">
        @foreach($galeria as $item)
        <div class="gallery__item" data-lightbox="{{ asset('storage/' . $item->imagem) }}" data-title="{{ $item->titulo }}" data-desc="{{ $item->descricao }}">
          <img src="{{ asset('storage/' . $item->imagem) }}" alt="{{ $item->titulo }}" loading="lazy" />
          <span>{{ $item->titulo }}</span>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- ===== FOOTER ===== -->
  @include('site.partials.footer')

@endsection
