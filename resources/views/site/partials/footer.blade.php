@php
  $homeUrl = route('home');
  $socialLinks = [];
  if (!empty($conteudo['social_facebook'])) $socialLinks['Facebook'] = ['url' => $conteudo['social_facebook'], 'label' => 'FB'];
  if (!empty($conteudo['social_instagram'])) $socialLinks['Instagram'] = ['url' => $conteudo['social_instagram'], 'label' => 'IG'];
  if (!empty($conteudo['social_twitter'])) $socialLinks['Twitter'] = ['url' => $conteudo['social_twitter'], 'label' => 'TW'];
  if (!empty($conteudo['social_youtube'])) $socialLinks['YouTube'] = ['url' => $conteudo['social_youtube'], 'label' => 'YT'];
@endphp

<!-- ===== FOOTER ===== -->
<footer class="footer">
  <div class="container footer__top">
    <div class="footer__brand">
      <img src="{{ asset('assets/logo-vetorial.png') }}" alt="Cruz de Ossos" />
      <p>{{ $conteudo['footer_descricao'] ?? 'Cavaleiros da estrada, irmãos da cruz. Irmandade desde 22/03/2025.' }}</p>
      @if(!empty($socialLinks))
      <div class="footer__social">
        @foreach($socialLinks as $nome => $s)
        <a href="{{ $s['url'] }}" target="_blank" rel="noopener" aria-label="{{ $nome }}">{{ $s['label'] }}</a>
        @endforeach
      </div>
      @endif
    </div>
    <div class="footer__col">
      <h4>Links rápidos</h4>
      <ul>
        @if($secoes['sec_sobre'] ?? true)<li><a href="{{ $homeUrl }}#about">O Clube</a></li>@endif
        @if($secoes['sec_eventos'] ?? true)<li><a href="{{ $homeUrl }}#events">Eventos</a></li>@endif
        @if($secoes['sec_galeria'] ?? true)<li><a href="{{ route('galeria') }}">Galeria</a></li>@endif
        @if($secoes['sec_contato'] ?? true)<li><a href="{{ $homeUrl }}#contact">Contato</a></li>@endif
      </ul>
    </div>
    <div class="footer__col">
      <h4>O Clube</h4>
      <ul>
        @if($secoes['sec_por_que_nos'] ?? true)<li><a href="{{ $homeUrl }}#why">Nossa Missão</a></li>@endif
        @if($secoes['sec_noticias'] ?? true)<li><a href="{{ $homeUrl }}#news">Notícias</a></li>@endif
        @if($secoes['sec_junte_se'] ?? true)<li><a href="{{ $homeUrl }}#join">Junte-se</a></li>@endif
        @if($secoes['sec_eventos'] ?? true)<li><a href="{{ $homeUrl }}#events">Eventos</a></li>@endif
      </ul>
    </div>
    <div class="footer__col">
      <h4>Contato</h4>
      <ul>
        @if(!empty($conteudo['footer_telefone']))<li>{{ $conteudo['footer_telefone'] }}</li>@endif
        @if(!empty($conteudo['footer_email']))<li>{{ $conteudo['footer_email'] }}</li>@endif
        @if(!empty($conteudo['footer_endereco']))<li>{{ $conteudo['footer_endereco'] }}</li>@endif
      </ul>
    </div>
  </div>
  <div class="footer__bottom">
    <div class="container footer__bottom-inner">
      <p>Copyright &copy; {{ date('Y') }} Cruz de Ossos. Todos os direitos reservados.</p>
      <p>Design por <a href="{{ $homeUrl }}">Cruz de Ossos</a></p>
    </div>
  </div>
</footer>
