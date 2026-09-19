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
          @if($secoes['sec_sede'] ?? false)<li><a href="{{ route('nossa-sede') }}">Nossa Sede</a></li>@endif
          @if($secoes['sec_noticias'] ?? true)<li><a href="{{ route('home') }}#news">Notícias</a></li>@endif
          @if($secoes['sec_contato'] ?? true)<li><a href="{{ route('home') }}#contact">Contato</a></li>@endif
        </ul>
      </nav>
      @if($secoes['sec_junte_se'] ?? true)<a href="{{ route('junte-se') }}" class="btn btn--primary header__cta">Junte-se ao Clube</a>@endif
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
  <section class="gallery gallery--full" id="gallery-full">
    <div class="container">
      @if(count($pastas) > 0)
      <div class="gallery__filters">
        <a href="{{ route('galeria') }}#gallery-full" class="gallery__filter {{ !$pastaAtual ? 'gallery__filter--active' : '' }}">Todas</a>
        @foreach($pastas as $pasta)
        <a href="{{ route('galeria', ['pasta' => $pasta]) }}#gallery-full" class="gallery__filter {{ $pastaAtual === $pasta ? 'gallery__filter--active' : '' }}">{{ $pasta }}</a>
        @endforeach
      </div>
      @endif
      <div class="gallery__grid">
        @foreach($galeria as $item)
        <div class="gallery__item" data-lightbox="{{ asset('storage/' . $item->imagem) }}" data-title="{{ $item->titulo }}" data-desc="{{ $item->descricao }}">
          <img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 3'%3E%3C/svg%3E"
               data-src="{{ asset('storage/' . $item->imagem) }}"
               alt="{{ $item->titulo }}"
               loading="lazy"
               decoding="async"
               class="lazy-img" />
          <span>{{ $item->titulo }}</span>
        </div>
        @endforeach
      </div>

      @if($galeria->isEmpty())
      <p class="gallery__empty">Nenhuma foto nesta pasta.</p>
      @endif

      @if($galeria->hasPages())
      <nav class="pagination" aria-label="Paginação da galeria">
        @if($galeria->onFirstPage())
          <span class="pagination__link pagination__link--disabled">&laquo; Anterior</span>
        @else
          <a class="pagination__link" href="{{ $galeria->previousPageUrl() }}#gallery-full" rel="prev">&laquo; Anterior</a>
        @endif

        @php
          $current = $galeria->currentPage();
          $last = $galeria->lastPage();
          $start = max(1, $current - 2);
          $end = min($last, $current + 2);
        @endphp

        @if($start > 1)
          <a class="pagination__link" href="{{ $galeria->url(1) }}#gallery-full">1</a>
          @if($start > 2)<span class="pagination__ellipsis">&hellip;</span>@endif
        @endif

        @for($page = $start; $page <= $end; $page++)
          @if($page == $current)
            <span class="pagination__link pagination__link--active">{{ $page }}</span>
          @else
            <a class="pagination__link" href="{{ $galeria->url($page) }}#gallery-full">{{ $page }}</a>
          @endif
        @endfor

        @if($end < $last)
          @if($end < $last - 1)<span class="pagination__ellipsis">&hellip;</span>@endif
          <a class="pagination__link" href="{{ $galeria->url($last) }}#gallery-full">{{ $last }}</a>
        @endif

        @if($galeria->hasMorePages())
          <a class="pagination__link" href="{{ $galeria->nextPageUrl() }}#gallery-full" rel="next">Próxima &raquo;</a>
        @else
          <span class="pagination__link pagination__link--disabled">Próxima &raquo;</span>
        @endif
      </nav>
      @endif
    </div>
  </section>

  <!-- ===== FOOTER ===== -->
  @include('site.partials.footer')

@endsection
