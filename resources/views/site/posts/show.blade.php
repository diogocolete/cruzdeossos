@extends('site.layout')

@section('title', $post->titulo . ' — Cruz de Ossos')

@section('content')

  @include('site.partials.topbar')
  @include('site.partials.header-interno')

  <!-- ===== POST ===== -->
  <section class="post">
    <div class="container post__container">
      <p class="section__kicker">Post</p>
      <h1 class="post__title">{{ $post->titulo }}</h1>

      <div class="post__meta">
        <a href="{{ route('integrantes.show', $post->integrante->slugPublico()) }}" class="post__autor">
          <span class="post__autor-foto" style="background-image:url('{{ $post->integrante->foto ? asset('storage/' . $post->integrante->foto) : asset('assets/vetor-patch-03.png') }}')"></span>
          {{ $post->integrante->apelido }}
        </a>
        <span class="post__data">
          Publicado em {{ ($post->published_at ?? $post->created_at)->format('d/m/Y') }}
          @if($post->updated_at > ($post->published_at ?? $post->created_at))
            · Atualizado em {{ $post->updated_at->format('d/m/Y') }}
          @endif
        </span>
      </div>

      @if($post->video || $post->youtubeEmbedUrl())
      <div class="post__video">
        @if($post->youtubeEmbedUrl())
          <iframe src="{{ $post->youtubeEmbedUrl() }}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        @else
          <video controls preload="metadata" src="{{ asset('storage/' . $post->video) }}"></video>
        @endif
      </div>
      @elseif($post->imagem)
      <div class="post__imagem">
        <img src="{{ asset('storage/' . $post->imagem) }}" alt="{{ $post->titulo }}" />
      </div>
      @endif

      <div class="post__conteudo">
        {!! $post->conteudo !!}
      </div>

      <div class="post__autor-card">
        <div class="post__autor-card-foto" style="background-image:url('{{ $post->integrante->foto ? asset('storage/' . $post->integrante->foto) : asset('assets/vetor-patch-03.png') }}')"></div>
        <div>
          <h3><a href="{{ route('integrantes.show', $post->integrante->slugPublico()) }}">{{ $post->integrante->apelido }}</a></h3>
          <span class="crew__role">{{ $post->integrante->cargo }}</span>
          @if($post->integrante->bio)<p>{{ str($post->integrante->bio)->limit(200) }}</p>@endif
        </div>
      </div>
    </div>
  </section>

  <!-- ===== OUTROS POSTS ===== -->
  @if($outrosPosts->isNotEmpty())
  <section class="news">
    <div class="container">
      <div class="section__head">
        <div>
          <p class="section__kicker">Continue lendo</p>
          <h2 class="section__title">Outros posts</h2>
        </div>
        <a href="{{ route('posts.index') }}" class="btn btn--ghost">Ver todos</a>
      </div>
      <div class="news__grid">
        @foreach($outrosPosts as $outro)
        <article class="news__card">
          <a href="{{ route('posts.show', $outro->slug) }}" class="news__img" style="background-image:url('{{ $outro->imagem ? asset('storage/' . $outro->imagem) : asset('assets/banner-3-esboco.png') }}')"></a>
          <div class="news__body">
            <div class="news__meta">
              <span>{{ ($outro->published_at ?? $outro->created_at)->format('d M Y') }}</span> ·
              <a href="{{ route('integrantes.show', $outro->integrante->slugPublico()) }}" class="news__autor">{{ $outro->integrante->apelido }}</a>
            </div>
            <h3><a href="{{ route('posts.show', $outro->slug) }}">{{ $outro->titulo }}</a></h3>
            <a href="{{ route('posts.show', $outro->slug) }}" class="news__more">Ler mais &rarr;</a>
          </div>
        </article>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  @include('site.partials.footer')

@endsection
