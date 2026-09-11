<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cruz de Ossos — Em Manutenção</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@300;400;500;600;700&family=Roboto+Condensed:wght@300;400;700&display=swap" rel="stylesheet" />
    <style>
        @font-face {
            font-family: 'Cristone';
            src: url('{{ asset("assets/Cristone.ttf") }}') format('truetype');
            font-display: swap;
        }
        :root {
            --red: #ED1C24;
            --red-deep: #8B2F26;
            --leather: #1A1411;
            --leather-2: #2A211C;
            --cream: #E8DDD0;
            --cream-dim: #B8A99A;
            --white: #F5EFE8;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { height: 100%; }
        body {
            font-family: 'Roboto Condensed', sans-serif;
            background:
                linear-gradient(rgba(12,9,7,.88), rgba(12,9,7,.94)),
                url('{{ asset("assets/couro2-background.png") }}');
            background-size: cover; background-position: center;
            color: var(--cream); min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            text-align: center; padding: 24px;
        }
        .manutencao { max-width: 560px; }
        .manutencao__logo { width: 140px; margin: 0 auto 36px; filter: drop-shadow(0 4px 20px rgba(0,0,0,.6)); }
        .manutencao__patch { width: 100px; margin: 0 auto 32px; opacity: .8; }
        .manutencao__kicker {
            font-family: 'Oswald', sans-serif; text-transform: uppercase;
            letter-spacing: 4px; font-size: 14px; font-weight: 600;
            color: var(--red); margin-bottom: 18px;
        }
        .manutencao__title {
            font-family: 'Cristone', 'Oswald', Impact, sans-serif;
            font-size: clamp(2.4rem, 6vw, 3.8rem); line-height: 1;
            text-transform: uppercase; color: var(--white);
            margin-bottom: 24px; text-shadow: 0 4px 20px rgba(0,0,0,.5);
        }
        .manutencao__title span { color: var(--red); display: block; }
        .manutencao__text {
            font-size: 17px; color: var(--cream-dim); line-height: 1.6;
            margin-bottom: 36px;
        }
        .manutencao__pulse {
            width: 14px; height: 14px; border-radius: 50%; background: var(--red);
            display: inline-block; margin-right: 10px; vertical-align: middle;
            animation: pulse 1.5s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: .4; transform: scale(1.3); }
        }
        .manutencao__social { display: flex; gap: 10px; justify-content: center; margin-top: 40px; }
        .manutencao__social a {
            width: 40px; height: 40px; display: grid; place-items: center;
            border: 1px solid rgba(237,28,36,.3); font-size: 11px; font-weight: 700;
            letter-spacing: 1px; color: var(--cream-dim); transition: all .25s ease;
        }
        .manutencao__social a:hover { background: var(--red); color: var(--white); border-color: var(--red); }
    </style>
</head>
<body>
    <div class="manutencao">
        <img src="{{ asset('assets/logo-vetorial.png') }}" alt="Cruz de Ossos" class="manutencao__logo" />
        <img src="{{ asset('assets/vetor-patch-03.png') }}" alt="Patch" class="manutencao__patch" />
        <p class="manutencao__kicker"><span class="manutencao__pulse"></span>Site em manutenção</p>
        <h1 class="manutencao__title">Voltamos logo<br /><span>Cruz de Ossos</span></h1>
        <p class="manutencao__text">
            Estamos fazendo alguns ajustes para melhorar a experiência da nossa irmandade.<br />
            A estrada continua — volte em breve.
        </p>
        <div class="manutencao__social">
            <a href="#" aria-label="Facebook">FB</a>
            <a href="#" aria-label="Instagram">IG</a>
            <a href="#" aria-label="Twitter">TW</a>
            <a href="#" aria-label="YouTube">YT</a>
        </div>
    </div>
</body>
</html>
