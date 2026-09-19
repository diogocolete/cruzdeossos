@extends('site.layout')

@section('title', ($conteudo['sede_titulo'] ?? 'Nossa Sede') . ' — Cruz de Ossos')

@section('content')

  @include('site.partials.topbar')
  @include('site.partials.header-interno')

  <!-- ===== PAGE HERO ===== -->
  <section class="page-hero">
    <div class="container">
      <p class="section__kicker">{{ $conteudo['sede_kicker'] }}</p>
      <h1 class="page-hero__title">{{ $conteudo['sede_titulo'] }}</h1>
    </div>
  </section>

  <!-- ===== SEDE ===== -->
  <section class="about" id="sede">
    <div class="container about__inner">
      @if($conteudo['sede_imagem'])
      <div class="about__img">
        <img src="{{ asset('storage/' . $conteudo['sede_imagem']) }}" alt="{{ $conteudo['sede_titulo'] }}" />
      </div>
      @endif
      <div class="about__text">
        <h2 class="section__title">{{ $conteudo['sede_titulo'] }}</h2>
        <p class="about__lead">{{ $conteudo['sede_texto'] }}</p>
      </div>
    </div>

    @if($conteudo['sede_maps_embed'])
    <div class="container sede__mapa">
      <iframe src="{{ $conteudo['sede_maps_embed'] }}" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Localização da sede"></iframe>
    </div>
    @endif
  </section>

  <!-- ===== FOTOS DA SEDE ===== -->
  @if($fotosSede->isNotEmpty())
  <section class="gallery gallery--full">
    <div class="container">
      <div class="section__head section__head--center">
        <p class="section__kicker">Momentos</p>
        <h2 class="section__title">Fotos da Sede</h2>
      </div>
      <div class="gallery__grid">
        @foreach($fotosSede as $item)
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
    </div>
  </section>
  @endif

  @include('site.partials.footer')

@endsection
