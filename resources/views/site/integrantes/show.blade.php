@extends('site.layout')

@section('title', $integrante->apelido . ' — Cruz de Ossos')

@section('content')

  @include('site.partials.topbar')
  @include('site.partials.header-interno')

  <!-- ===== MEMBER PROFILE ===== -->
  <section class="member">
    <div class="container">
      <div class="member__card">
        <div class="member__foto" style="background-image:url('{{ $integrante->foto ? asset('storage/' . $integrante->foto) : asset('assets/vetor-patch-03.png') }}')"></div>
        <div class="member__info">
          <p class="section__kicker">Integrante</p>
          <h1 class="member__nome">{{ $integrante->apelido }}</h1>
          <span class="member__cargo">{{ $integrante->cargo }}</span>
          @if($integrante->bio)
          <div class="member__bio">{!! nl2br(e($integrante->bio)) !!}</div>
          @endif
        </div>
      </div>
    </div>
  </section>

  <!-- ===== POSTS DO MEMBRO ===== -->
  <section class="news">
    <div class="container">
      <div class="section__head">
        <div>
          <p class="section__kicker">Publicações</p>
          <h2 class="section__title">Posts de {{ $integrante->apelido }}</h2>
        </div>
      </div>
      <div class="news__grid">
        @forelse($posts as $post)
        <article class="news__card">
          <a href="{{ route('posts.show', $post->slug) }}" class="news__img" style="background-image:url('{{ $post->imagem ? asset('storage/' . $post->imagem) : asset('assets/banner-3-esboco.png') }}')"></a>
          <div class="news__body">
            <div class="news__meta"><span>{{ ($post->published_at ?? $post->created_at)->format('d M Y') }}</span></div>
            <h3><a href="{{ route('posts.show', $post->slug) }}">{{ $post->titulo }}</a></h3>
            <a href="{{ route('posts.show', $post->slug) }}" class="news__more">Ler mais &rarr;</a>
          </div>
        </article>
        @empty
        <p style="grid-column:1/-1;color:var(--cream-dim);">Este integrante ainda não publicou posts.</p>
        @endforelse
      </div>
    </div>
  </section>

  @include('site.partials.footer')

@endsection
