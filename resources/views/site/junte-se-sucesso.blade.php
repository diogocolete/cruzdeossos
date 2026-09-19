@extends('site.layout')

@section('title', 'Cadastro enviado — Cruz de Ossos')

@section('content')

  @include('site.partials.topbar')
  @include('site.partials.header-interno')

  <!-- ===== PAGE HERO ===== -->
  <section class="page-hero">
    <div class="container">
      <p class="section__kicker">Processo de inscrição concluído</p>
      <h1 class="page-hero__title">Cadastro enviado!</h1>
      <p class="page-hero__subtitle">{{ str($inscricao->nome_completo)->before(' ') }}, recebemos seu cadastro completo. A diretoria vai analisar sua inscrição e entrará em contato pelos dados informados. Nos vemos na estrada!</p>
      <div style="margin-top:32px;">
        <a href="{{ route('home') }}" class="btn btn--primary">Voltar ao início</a>
      </div>
    </div>
  </section>

  @include('site.partials.footer')

@endsection
