@extends('site.layout')

@section('title', $noticia->titulo . ' — Cruz de Ossos')

@section('content')

  @include('site.partials.topbar')
  @include('site.partials.header-interno')

  <!-- ===== NOTÍCIA ===== -->
  <section class="post">
    <div class="container post__container">
      <p class="section__kicker">Notícia</p>
      <h1 class="post__title">{{ $noticia->titulo }}</h1>

      <div class="post__meta">
        <span class="post__autor">{{ $noticia->user->name ?? 'Cruz de Ossos' }}</span>
        <span class="post__data">
          Publicado em {{ $noticia->data_publicacao->format('d/m/Y') }}
          @if($noticia->updated_at > $noticia->data_publicacao)
            · Atualizado em {{ $noticia->updated_at->format('d/m/Y') }}
          @endif
        </span>
      </div>

      @if($noticia->imagem)
      <div class="post__imagem">
        <img src="{{ asset('storage/' . $noticia->imagem) }}" alt="{{ $noticia->titulo }}" />
      </div>
      @endif

      @if($noticia->resumo)
      <p class="post__resumo">{{ $noticia->resumo }}</p>
      @endif

      <div class="post__conteudo">
        {!! $noticia->conteudo !!}
      </div>
    </div>
  </section>

  <!-- ===== OUTRAS NOTÍCIAS ===== -->
  @if($outrasNoticias->isNotEmpty())
  <section class="news">
    <div class="container">
      <div class="section__head">
        <div>
          <p class="section__kicker">Continue lendo</p>
          <h2 class="section__title">Outras notícias</h2>
        </div>
        <a href="{{ route('home') }}#news" class="btn btn--ghost">Ver todas</a>
      </div>
      <div class="news__grid">
        @foreach($outrasNoticias as $outra)
        <article class="news__card">
          <a href="{{ route('noticias.show', $outra->slug) }}" class="news__img" style="background-image:url('{{ $outra->imagem ? asset('storage/' . $outra->imagem) : asset('assets/banner-3-esboco.png') }}')"></a>
          <div class="news__body">
            <div class="news__meta">
              <span>{{ $outra->data_publicacao->format('d M Y') }}</span>
            </div>
            <h3><a href="{{ route('noticias.show', $outra->slug) }}">{{ $outra->titulo }}</a></h3>
            <a href="{{ route('noticias.show', $outra->slug) }}" class="news__more">Ler mais &rarr;</a>
          </div>
        </article>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  @include('site.partials.footer')

@endsection
