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
        @if($secoes['sec_galeria'] ?? true)<li><a href="{{ route('galeria') }}" class="{{ request()->routeIs('galeria') ? 'active' : '' }}">Galeria</a></li>@endif
        @if($secoes['sec_sede'] ?? false)<li><a href="{{ route('nossa-sede') }}" class="{{ request()->routeIs('nossa-sede') ? 'active' : '' }}">Nossa Sede</a></li>@endif
        @if($secoes['sec_posts'] ?? true)<li><a href="{{ route('posts.index') }}" class="{{ request()->routeIs('posts.*') ? 'active' : '' }}">Posts</a></li>@endif
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
