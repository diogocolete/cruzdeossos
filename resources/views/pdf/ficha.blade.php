<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<style>
    @page { margin: 60px 80px; }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        font-family: 'DejaVu Sans Condensed', 'DejaVu Sans', sans-serif;
        color: #2b2b2b;
        font-size: 17px;
    }

    .watermark {
        position: fixed;
        top: 200px;
        left: 50%;
        margin-left: -280px;
        width: 560px;
        z-index: -1;
    }

    .brand {
        font-size: 13px;
        color: #8a8a8a;
        font-weight: bold;
    }
    .nome {
        font-size: 38px;
        font-weight: bold;
        color: #3a3a3a;
        margin-top: 2px;
    }
    .linha {
        border-bottom: 4px solid #ED1C24;
        margin: 10px 0 14px;
    }
    .data {
        color: #ED1C24;
        font-size: 13px;
        font-weight: bold;
        letter-spacing: 2px;
        text-transform: uppercase;
    }
    .rev {
        color: #b0a49c;
        font-size: 11px;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-top: 3px;
    }

    .foto {
        position: absolute;
        top: 0;
        right: 0;
        width: 150px;
        border: 2px solid #e8ddd0;
    }
    .foto img { width: 100%; }

    .bloco { margin-top: 42px; position: relative; }
    .conteudo { width: 72%; }

    .titulo-secao {
        font-size: 21px;
        font-weight: bold;
        text-decoration: underline;
        margin-bottom: 14px;
        color: #2b2b2b;
    }
    .nome-ficha { font-weight: bold; font-size: 19px; }
    .nascido { font-size: 17px; margin-bottom: 22px; }

    .campo { margin-bottom: 8px; font-size: 17px; }
    .campo .label { font-weight: bold; }

    .endereco { margin-top: 34px; }
    .endereco-texto { font-weight: bold; font-size: 17px; line-height: 1.5; }

    .contatos { margin-top: 34px; }
    .contato-item { font-weight: bold; font-size: 17px; margin-bottom: 10px; }
</style>
</head>
<body>

@if($watermark)
<img class="watermark" src="{{ $watermark }}" alt="" />
@endif

<div class="brand">Cruz de Ossos - Irmandade</div>
<div class="nome">{{ $ficha->nome_completo }}</div>
<div class="linha"></div>
<div class="data">{{ ($dataRevisao ?? now())->locale('pt_BR')->isoFormat('D [DE] MMMM [DE] YYYY') }}</div>
<div class="rev">Revisão nº {{ $revisao }}</div>

<div class="bloco">
    @if($foto)
    <div class="foto"><img src="{{ $foto }}" alt="" /></div>
    @endif

    <div class="conteudo">
        <div class="titulo-secao">FICHA INTEGRANTE</div>
        <div class="nome-ficha">{{ $ficha->nome_completo }}</div>
        <div class="nascido">Nascido: {{ $ficha->data_nascimento?->format('d/m/Y') ?? '—' }}</div>

        <div class="campo"><span class="label">APELIDO:</span> {{ $ficha->apelido ?? '—' }}</div>
        <div class="campo"><span class="label">CARGO:</span> {{ $ficha->cargo ?? '—' }}</div>
        <div class="campo"><span class="label">TIPO SANGUÍNEO:</span> {{ $ficha->tipo_sanguineo ?? '—' }}</div>
        <div class="campo"><span class="label">ALÉRGICO:</span> {{ $ficha->alergias ?? '—' }}</div>
        <div class="campo"><span class="label">REMÉDIOS:</span> {{ $ficha->remedios ?? '—' }}</div>
        <div class="campo"><span class="label">DOENÇAS:</span> {{ $ficha->doencas ?? '—' }}</div>
        <div class="campo"><span class="label">CIRURGIAS:</span> {{ $ficha->cirurgias ?? '—' }}</div>

        <div class="endereco">
            <div class="titulo-secao">ENDEREÇO:</div>
            <div class="endereco-texto">{{ $ficha->enderecoCompleto() ?: '—' }}</div>
        </div>

        <div class="contatos">
            <div class="titulo-secao">CONTATO:</div>
            @forelse($ficha->contatos ?? [] as $contato)
                <div class="contato-item">
                    {{ $contato['nome'] ?? '' }}{{ !empty($contato['parentesco']) ? ' (' . $contato['parentesco'] . ')' : '' }}: {{ $contato['telefone'] ?? '' }}
                </div>
            @empty
                <div class="contato-item">—</div>
            @endforelse
        </div>
    </div>
</div>

</body>
</html>
