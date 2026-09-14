@php
  $socialLinks = [];
  if (!empty($conteudo['social_facebook'])) $socialLinks['Facebook'] = ['url' => $conteudo['social_facebook'], 'label' => 'FB'];
  if (!empty($conteudo['social_instagram'])) $socialLinks['Instagram'] = ['url' => $conteudo['social_instagram'], 'label' => 'IG'];
  if (!empty($conteudo['social_twitter'])) $socialLinks['Twitter'] = ['url' => $conteudo['social_twitter'], 'label' => 'TW'];
  if (!empty($conteudo['social_youtube'])) $socialLinks['YouTube'] = ['url' => $conteudo['social_youtube'], 'label' => 'YT'];
@endphp

<!-- ===== TOP BAR ===== -->
<div class="topbar">
  <div class="container topbar__inner">
    @if(!empty($conteudo['footer_email']))
    <a href="mailto:{{ $conteudo['footer_email'] }}" class="topbar__email">{{ $conteudo['footer_email'] }}</a>
    @endif
    @if(!empty($socialLinks))
    <div class="topbar__social">
      @foreach($socialLinks as $nome => $s)
      <a href="{{ $s['url'] }}" target="_blank" rel="noopener" aria-label="{{ $nome }}">{{ $s['label'] }}</a>
      @endforeach
    </div>
    @endif
  </div>
</div>
