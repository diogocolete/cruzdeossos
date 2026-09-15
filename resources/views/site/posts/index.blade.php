@extends('site.layout')

@section('title', 'Posts — Cruz de Ossos')

@section('content')

  @include('site.partials.topbar')
  @include('site.partials.header-interno')

  <!-- ===== PAGE HERO ===== -->
  <section class="page-hero">
    <div class="container">
      <p class="section__kicker">{{ $conteudo['posts_kicker'] ?? 'Blog da irmandade' }}</p>
      <h1 class="page-hero__title">{{ $conteudo['posts_titulo'] ?? 'Posts' }}</h1>
      <p class="page-hero__subtitle">{{ $conteudo['posts_subtitulo'] ?? '' }}</p>
    </div>
  </section>

  <!-- ===== POSTS ===== -->
  <section class="news news--full">
    <div class="container">
      <div class="news__grid">
        @forelse($posts as $post)
        <article class="news__card">
          <a href="{{ route('posts.show', $post->slug) }}" class="news__img" style="background-image:url('{{ $post->imagem ? asset('storage/' . $post->imagem) : asset('assets/banner-3-esboco.png') }}')"></a>
          <div class="news__body">
            <div class="news__meta">
              <span>{{ ($post->published_at ?? $post->created_at)->format('d M Y') }}</span> ·
              <a href="{{ route('integrantes.show', $post->integrante->slugPublico()) }}" class="news__autor">{{ $post->integrante->apelido }}</a>
            </div>
            <h3><a href="{{ route('posts.show', $post->slug) }}">{{ $post->titulo }}</a></h3>
            <p>{{ str(strip_tags($post->conteudo))->limit(140) }}</p>
            <a href="{{ route('posts.show', $post->slug) }}" class="news__more">Ler mais &rarr;</a>
          </div>
        </article>
        @empty
        <p style="grid-column:1/-1;text-align:center;color:var(--cream-dim);">Nenhum post publicado ainda.</p>
        @endforelse
      </div>

      <div class="posts__pagination">
        {{ $posts->links('pagination::bootstrap-5') }}
      </div>
    </div>
  </section>

  @include('site.partials.footer')

@endsection
