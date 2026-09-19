@extends('site.layout')

@section('title', 'Complete seu cadastro — Cruz de Ossos')

@section('content')

  @include('site.partials.topbar')
  @include('site.partials.header-interno')

  <!-- ===== PAGE HERO ===== -->
  <section class="page-hero">
    <div class="container">
      <p class="section__kicker">Processo de inscrição — Etapa 2 de 2</p>
      <h1 class="page-hero__title">Complete seu cadastro</h1>
      <p class="page-hero__subtitle">Olá, {{ str($inscricao->nome_completo)->before(' ') }}! Seu cadastro básico foi recebido. Agora precisamos de mais alguns dados para a diretoria analisar sua inscrição.</p>
    </div>
  </section>

  <!-- ===== JOIN STEP 2 ===== -->
  <section class="join join--page">
    <div class="container">
      <div class="join-page__content" style="max-width:720px;margin:0 auto;">
        <form class="join__form join__form--full" method="POST" action="{{ route('junte-se.etapa2.store', $inscricao->token) }}">
          @csrf
          @if($errors->any())
          <div class="join__msg join__msg--erro">{{ $errors->first() }}</div>
          @endif
          <div class="join__fields">
            <input type="text" name="nome_completo" placeholder="Nome completo *" value="{{ old('nome_completo', $inscricao->nome_completo) }}" required />
            <input type="text" name="endereco" placeholder="Endereço completo *" value="{{ old('endereco', $inscricao->endereco) }}" required />
            <input type="text" name="cpf" placeholder="CPF *" value="{{ old('cpf') }}" maxlength="14" required />
            <input type="text" name="rg" placeholder="RG *" value="{{ old('rg') }}" maxlength="20" required />
            <input type="date" name="data_nascimento" placeholder="Data de nascimento *" value="{{ old('data_nascimento') }}" required />
            <input type="text" name="moto" placeholder="Moto (marca/modelo) *" value="{{ old('moto') }}" required />

            <div class="join__radio-group">
              <span class="join__radio-label">Já pertenceu a algum clube? *</span>
              <label class="join__radio">
                <input type="radio" name="ja_pertenceu_clube" value="0" {{ old('ja_pertenceu_clube') === '0' || old('ja_pertenceu_clube') === null ? 'checked' : '' }} required /> Não
              </label>
              <label class="join__radio">
                <input type="radio" name="ja_pertenceu_clube" value="1" {{ old('ja_pertenceu_clube') === '1' ? 'checked' : '' }} /> Sim
              </label>
            </div>
            <input type="text" name="clube_anterior" placeholder="Qual clube? (se respondeu sim)" value="{{ old('clube_anterior') }}" />

            <input type="text" name="hp_field" class="join__hp" tabindex="-1" autocomplete="off" aria-hidden="true" />
          </div>
          <button type="submit" class="btn btn--primary">Enviar cadastro completo</button>
        </form>
      </div>
    </div>
  </section>

  @include('site.partials.footer')

@endsection
