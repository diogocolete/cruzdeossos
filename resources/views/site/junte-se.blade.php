@extends('site.layout')

@section('title', ($conteudo['junte_se_titulo'] ?? 'Junte-se') . ' — Cruz de Ossos')

@section('content')

  @include('site.partials.topbar')
  @include('site.partials.header-interno')

  <!-- ===== PAGE HERO ===== -->
  <section class="page-hero">
    <div class="container">
      <p class="section__kicker">{{ $conteudo['junte_se_kicker'] }}</p>
      <h1 class="page-hero__title">{{ $conteudo['junte_se_titulo'] }}</h1>
    </div>
  </section>

  <!-- ===== JOIN PAGE ===== -->
  <section class="join join--page">
    <div class="container join-page__grid">
      <div class="join-page__media">
        <img src="{{ asset('storage/' . $conteudo['junte_se_imagem']) }}" alt="Irmãos da Cruz de Ossos na estrada" />
      </div>
      <div class="join-page__content">
        <p class="join-page__lead">{{ $conteudo['junte_se_texto'] }}</p>
        <form class="join__form join__form--full" method="POST" action="{{ route('junte-se.store') }}">
          @csrf
          @if(session('inscricao_ok'))
          <div class="join__msg join__msg--ok">{{ session('inscricao_ok') }}</div>
          @endif
          @if($errors->any())
          <div class="join__msg join__msg--erro">{{ $errors->first() }}</div>
          @endif
          <div class="join__fields">
            <input type="text" name="nome_completo" placeholder="Nome completo *" value="{{ old('nome_completo') }}" required />
            <input type="text" name="rede_social" placeholder="Instagram / rede social" value="{{ old('rede_social') }}" />
            <input type="email" name="email" placeholder="E-mail" value="{{ old('email') }}" />
            <input type="tel" name="telefone" placeholder="Telefone" value="{{ old('telefone') }}" />
            <input type="tel" name="whatsapp" placeholder="WhatsApp" value="{{ old('whatsapp') }}" />
            <input type="text" name="endereco" placeholder="Endereço (cidade/bairro)" value="{{ old('endereco') }}" />
            <input type="text" name="hp_field" class="join__hp" tabindex="-1" autocomplete="off" aria-hidden="true" />
          </div>
          <button type="submit" class="btn btn--primary">{{ $conteudo['junte_se_btn'] ?: 'Enviar inscrição' }}</button>
        </form>
      </div>
    </div>
  </section>

  <!-- ===== EVOLUÇÃO (como funciona a entrada) ===== -->
  @if($evolucaoPassos->isNotEmpty())
  <section class="evolution">
    <div class="container">
      <div class="section__head section__head--center">
        <p class="section__kicker">{{ $conteudo['evolucao_kicker'] }}</p>
        <h2 class="section__title">{{ $conteudo['evolucao_titulo'] }}</h2>
      </div>
      <div class="evolution__track">
        <div class="evolution__line"></div>
        @foreach($evolucaoPassos as $passo)
        <div class="evolution__step">
          <div class="evolution__num">{{ $passo->numero }}</div>
          <div class="evolution__badge{{ $passo->destacado ? ' evolution__badge--full' : '' }}">
            <img src="{{ $passo->imagem ? asset('storage/' . $passo->imagem) : asset('assets/vetor-patch-03.png') }}" alt="{{ $passo->tag }}" />
          </div>
          <div class="evolution__body">
            <span class="evolution__tag{{ $passo->destacado ? ' evolution__tag--full' : '' }}">{{ $passo->tag }}</span>
            <h3>{{ $passo->titulo }}</h3>
            <p>{!! $passo->descricao !!}</p>
          </div>
        </div>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  @include('site.partials.footer')

@endsection
