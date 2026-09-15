# AGENTS.md — Cruz de Ossos

## Identidade

- **Nome:** Cruz de Ossos
- **Tipo:** Irmandade (NÃO usar "MC", "Moto Clube" ou "Motorcycle Club")
- **Fundação:** 22/03/2025
- **Slogan:** Irmãos da estrada. Cavaleiros, história e irmandade.

## Paleta de Cores

| Cor | Hex | Uso |
|-----|-----|-----|
| Vermelho marca | `#ED1C24` | primária (logo/patch) |
| Vermelho profundo | `#8B2F26` | hover/acentos |
| Couro escuro | `#1A1411` | fundo principal |
| Couro médio | `#2A211C` | cartões/seções |
| Caramelo | `#AF8D7E` | tons de couro |
| Creme | `#E8DDD0` | texto |

## Tipografia

- **Fonte de marca:** Cristone (arquivo `assets/Cristone.ttf`) — usada em títulos
- **Fonte de apoio (headings):** Oswald (Google Fonts)
- **Fonte de corpo:** Roboto Condensed (Google Fonts)

## Assets

Localizados em `arquivos/`:
- `logo-vetorial.png` — logo principal
- `vetor-patch-03.png` — patch/cruz
- `Cristone.ttf` — fonte de marca
- `couro2-background.png` — textura de couro para fundos
- `banner-1 a 5-esboco.png` — banners para hero e seções
- `integrantes/` — fotos dos integrantes (JPG) e fichas (PDF)

## Estrutura do Projeto

```
cruzdeossos/
├── arquivos/          # assets originais (imagens, fonte, PDFs)
│   └── integrantes/   # fotos e fichas dos integrantes
├── draft/             # esboço do site
│   ├── assets/        # assets copiados para o draft
│   ├── css/style.css  # estilos
│   ├── js/main.js     # scripts
│   ├── index.html     # página principal
│   ├── favicon.ico    # favicon gerado da logo
│   ├── start-ngrok.sh # script para iniciar ngrok
│   └── site.webmanifest
└── AGENTS.md          # este arquivo
```

## Integrantes

Usar **apenas apelido e cargo**. NÃO incluir dados sensíveis dos PDFs (endereço, contato, tipo sanguíneo, saúde, etc.).

| Apelido | Cargo | Foto |
|---------|-------|------|
| Barnabé | Presidente | barnabe.jpg |
| Colete | Secretário | colete.jpg |
| Thiago | Sargente de Armas | thiago.jpg |
| Maceno | Membro | mnaceno.jpg |
| Juarez | Membro | juarez.jpg |

## Evolução na Irmandade

1. **Ride** — Roda conosco para ver se é isso que você quer, sem colete
2. **Prospect** — Após o período probatório, recebe bolacha de metal no peito e a inscrição "Prospect" nas costas
3. **Full Patch** — Após adquirir a confiança dos membros, vira irmão de verdade

## Seções do Site

1. Top bar (email + redes sociais)
2. Header (logo, menu, CTA)
3. Hero slider (3 slides, auto-play)
4. Sobre / Quem somos
5. Eventos (timeline de eventos importantes)
6. Por Que Nós
7. Evolução na Irmandade (Ride → Prospect → Full Patch)
8. CTA banner
9. Galeria (grid com lightbox)
10. Integrantes (cards com foto, apelido e cargo)
11. Notícias
12. Depoimentos
13. Junte-se (newsletter)
14. Contato (formulário + dados)
15. Footer

## Regras Importantes

- **NUNCA** referenciar "MC", "Moto Clube" ou "Motorcycle Club" — somos uma **Irmandade**
- **NUNCA** usar a palavra "cavalgada" — usar "passeio", "passeios" ou "ride"
- **NUNCA** expor dados sensíveis dos integrantes (apenas apelido e cargo)
- A data de fundação é **22/03/2025** — não inventar tempo de existência
- Não usar selo de "anos de estrada" — a irmandade é recente

### Privacidade da Ficha do Integrante (regra permanente)

A **ficha do integrante** (model `Ficha`, tabela `fichas`) contém dados sensíveis: nome completo, endereço, saúde, contatos de emergência e anexos.

- **Visitante do site NUNCA pode ver nenhum dado da ficha** — ela existe apenas na área privada (`/admin`). Nenhuma rota, view ou API pública pode expor esses dados.
- **Um membro NUNCA pode ver a ficha de outro membro** — apenas a própria ficha (vinculada via `users.integrante_id`).
- **Presidente e Secretário** podem ver e modificar **todas** as fichas.
- **Toda alteração gera uma nova revisão** (`ficha_revisions`) — nunca sobrescrever dados anteriores; sempre incrementar `fichas.revisao` e gravar snapshot.
- **Anexos da ficha ficam em disco privado** (`local`), nunca em `public/storage`.
- Enforce: `FichaResource::getEloquentQuery()` (escopo), `FichaResource::podeVerFicha()` e `FichaPdfController` (abort 403).

## Comandos

- **Servir o draft local:** `cd draft && python3 -m http.server 8123`
- **Iniciar ngrok:** `cd draft && ./start-ngrok.sh`
- **Painel ngrok:** `http://localhost:4040`

## Desenvolvimento

### Tecnologias em Desenvolvimento

| Tecnologia | Versão | Uso |
|------------|--------|-----|
| Laravel | 11 | Framework PHP (aplicação principal) |
| PHP | 8.3 | Runtime |
| Filament | 3 | Painel administrativo |
| Spatie Laravel Permission | 6.25 | Controle de papéis e permissões |
| Intervention Image | 4.3 | Processamento de imagens (uploads) |
| MySQL | 8.0 | Banco de dados |
| Apache | 2.4 | Servidor web |
| WSL2 | — | Ambiente Linux dentro do Windows |
| Certificado SSL autoassinado | — | Para HTTPS local (`cruzdeossos.dev`) |

### Ambiente Local

| Item | Valor |
|------|-------|
| Plataforma | WSL2 (Ubuntu) no Windows |
| Diretório do projeto | `/var/www/html/cruzdeossos/sistema` |
| Document root do Apache | `/var/www/html/cruzdeossos/sistema/public` |
| APP_ENV | `local` |
| APP_DEBUG | `true` |
| APP_URL | `https://cruzdeossos.dev` |
| DB_CONNECTION | `mysql` |
| DB_HOST | `127.0.0.1` |
| DB_DATABASE | `cruz_de_ossos` |
| FILESYSTEM_DISK | `local` |
| SESSION_DRIVER | `database` |
| QUEUE_CONNECTION | `database` |
| CACHE_STORE | `database` |

### Domínio Local

| Domínio | Status |
|--------|--------|
| `https://cruzdeossos.dev` | Ativo (SSL autoassinado) |

O domínio `cruzdeossos.dev` é resolvido localmente via configuração do Apache (`/etc/apache2/sites-available/cruzdeossos.dev.conf`) com certificado SSL autoassinado em `/etc/apache2/ssl/cruzdeossos.dev.{crt,key}`.

### Painel Administrativo Local

| Item | Valor |
|------|-------|
| URL | `https://cruzdeossos.dev/admin` |
| Usuário admin | `admin@cruzdeossos.com.br` |
| Senha | `cruzdeossos2025` (ambiente local — alterar em produção) |

### Comandos úteis no Desenvolvimento

```bash
# Entrar no diretório do projeto
cd /var/www/html/cruzdeossos/sistema

# Servir a aplicação (alternativa ao Apache)
php artisan serve

# Rodar migrations
php artisan migrate

# Rodar seeders
php artisan db:seed

# Limpar caches
php artisan optimize:clear

# Criar storage link (symlink public/storage -> storage/app/public)
php artisan storage:link

# Rodar testes
php artisan test

# Tinker (REPL do Laravel)
php artisan tinker
```

### Estrutura de Diretórios Local

```
/var/www/html/cruzdeossos/
├── arquivos/                       # assets originais (imagens, fonte, PDFs)
│   └── integrantes/                # fotos e fichas dos integrantes
├── draft/                         # esboço do site (HTML/CSS/JS estático)
│   ├── assets/                     # assets copiados para o draft
│   ├── css/style.css               # estilos do esboço
│   ├── js/main.js                  # scripts do esboço
│   └── index.html                  # página principal do esboço
├── sistema/                        # aplicação Laravel (repositório git)
│   ├── app/                        # código da aplicação
│   │   ├── Filament/               # resources e pages do painel admin
│   │   ├── Http/Controllers/        # controllers (SiteController, etc.)
│   │   ├── Livewire/               # componentes Livewire
│   │   └── Models/                 # modelos Eloquent
│   ├── resources/views/site/       # views Blade do site público
│   ├── public/css/style.css        # CSS do site em produção
│   ├── public/js/main.js           # JS do site em produção
│   ├── storage/app/public/galeria/ # 477 fotos da galeria
│   ├── storage/app/public/integrantes/  # fotos dos integrantes
│   ├── .env                        # configurações locais (NÃO commitado)
│   └── AGENTS.md                   # este arquivo
└── AGENTS.md                        # cópia na raiz do projeto
```

### Funcionalidades da área de membros

| Recurso | Onde | Detalhe |
|---------|------|---------|
| Posts | `/admin/posts` (privado) → `/posts`, `/posts/{slug}` (público) | Título, membro, datas, editor rico, imagem, vídeo (upload ou URL YouTube/Vimeo) |
| Destaque na home | Toggle `destaque` no post (só diretoria) | Home mostra até 6: destaques primeiro, completa com recentes |
| Página do membro | `/integrantes/{slug}` (público) | Foto, apelido, cargo, bio + posts do membro |
| Meu Perfil | `/admin/meu-perfil` | Membro edita foto/bio/slug da própria página pública |
| Ficha do integrante | `/admin/fichas` (privado, com revisões) | Ver "Privacidade da Ficha" abaixo — acesso restrito |
| Integrações sociais | Configurações → Integrações | Checkbox por rede no post; envio via fila; log em `post_shares` |

- Login admin (`users`) ↔ membro (`integrantes`) via `users.integrante_id`.
- Diretoria = papéis `Presidente` + `Secretário` (`$user->isDiretoria()`): gerenciam todos os posts/fichas e marcam destaque. Membros comuns só editam os próprios posts e a própria ficha.
- Compartilhamento social: `PostShare` registra cada envio (pendente/enviado/erro/desativado); `SocialPublisher` só dispara se a rede estiver **ativa E configurada** — caso contrário marca `desativado`. Reenvio manual disponível no log do post.

### Repositório Git

| Item | Valor |
|------|-------|
| URL | `https://github.com/diogocolete/cruzdeossos` |
| Branch principal | `main` |
| Diretório do repo | `/var/www/html/cruzdeossos/sistema` |

### Diferenças entre Local e Produção

| Item | Desenvolvimento | Produção |
|------|-----------------|----------|
| APP_ENV | `local` | `production` |
| APP_DEBUG | `true` | `false` |
| APP_URL | `https://cruzdeossos.dev` | `https://www.cruzdeossos.com.br` |
| FILESYSTEM_DISK | `local` | `public` |
| SSL | Autoassinado | Cloudflare Origin Certificate (válido até 2041) |
| Deploy | Manual (`git push`) | Deployer.org (local, via SSH) |
| Cache | Database | Arquivo (`route:cache`, `view:cache`) |

## Produção

### Tecnologias em Produção

| Tecnologia | Versão | Uso |
|------------|--------|-----|
| Laravel | 11 | Framework PHP (aplicação principal) |
| PHP | 8.5 | Runtime |
| Filament | 3 | Painel administrativo |
| Spatie Laravel Permission | — | Controle de papéis e permissões |
| Intervention Image | — | Processamento de imagens (uploads) |
| MySQL | 8.4 | Banco de dados |
| Apache | 2.4 | Servidor web |
| Cloudflare Origin Certificate | — | Certificado SSL (válido até 2041) |
| Cloudflare | — | DNS e proxy (modo Full Strict) |
| Deployer.org | 6.8 | Deploy via SSH com releases e rollback |

### Servidor: Hostinger VPS

| Item | Valor |
|------|-------|
| IP | `2.25.96.11` |
| OS | Ubuntu 26.04.1 LTS |
| Usuário SSH | `deploy` (grupo www-data) |
| Acesso SSH local | `ssh -i ~/.ssh/colete_hostinger deploy@2.25.96.11` |
| Deploy path | `/var/www/html/cruzdeossos` |
| Document root do Apache | `/var/www/html/cruzdeossos/current/public/` |

**Credenciais:** As senhas de SSH e banco de dados NÃO estão neste arquivo (evitar commit de segredos). Consulte o administrador do sistema ou o gerenciador de senhas da equipe.

### Estrutura de deploy (Deployer)

```
/var/www/html/cruzdeossos/
├── current -> releases/N    # symlink para release ativo
├── releases/                 # releases históricos (rollback)
│   ├── 1/
│   └── 2/
├── shared/                   # persistido entre releases
│   ├── .env                  # config de produção (NÃO sobrescrever)
│   └── storage/              # storage do Laravel (sessions, logs, uploads)
│       └── app/public/
│           ├── galeria/      # 477 fotos da galeria
│           ├── integrantes/  # fotos dos integrantes
│           └── banners/      # imagens de banners
└── .dep/                     # estado do Deployer
```

### Domínios

| Domínio | Status |
|--------|--------|
| `https://cruzdeossos.com.br` | Ativo (Cloudflare Origin Certificate) |
| `https://www.cruzdeossos.com.br` | Ativo (Cloudflare Origin Certificate) |

### Apache vhost

- Config: `/etc/apache2/sites-available/cruzdeossos.com.br.conf`
- DocumentRoot: `/var/www/html/cruzdeossos/current/public/`
- Porta 80: redirect 301 para HTTPS
- Porta 443: SSL com Cloudflare Origin Certificate
  - Cert: `/etc/ssl/cloudflare/cruzdeossos.pem`
  - Key: `/etc/ssl/cloudflare/cruzdeossos.key`

### Banco de dados

- **DB**: `cruzdeossos` (MySQL, localhost)
- **Usuário**: `cruzdeossos`
- **Senha**: ver arquivo `/root/.cruzdeossos_db_pw` na VPS

### Painel Administrativo

| Item | Valor |
|------|-------|
| URL | `https://www.cruzdeossos.com.br/admin` |
| Usuário admin | `admin@cruzdeossos.com.br` |
| Senha | Consulte o administrador (não commitada) |

### Deploy com Deployer.org

#### Pré-requisitos (máquina local)

- PHP CLI instalado
- `deployer.phar` na raiz do repo (baixar se faltar: `curl -LO https://deployer.org/deployer.phar && chmod +x deployer.phar`)
- Chave SSH `~/.ssh/colete_hostinger` com acesso ao usuário `deploy` na VPS

#### Comandos (rodar na raiz do repo)

```bash
# Deploy (faz git pull, composer install, cache, permissões, symlink, apache reload)
php deployer.phar deploy production

# Voltar para release anterior
php deployer.phar rollback production

# Ver releases disponíveis
php deployer.phar current production
php deployer.phar releases production

# Verbose
php deployer.phar deploy production -v
```

#### O que o deploy faz (pipeline)

1. `deploy:prepare` — cria dirs releases/, shared/, .dep/
2. `deploy:lock` — bloqueia deploy concorrente
3. `deploy:release` — prepara novo release
4. `deploy:update_code` — git clone na VPS
5. `deploy:shared` — symlink .env e storage do shared
6. `deploy:vendors` — composer install
7. `artisan:cache` — clear + migrate + route:cache + view:cache (sem config:cache!)
8. `artisan:storage:link` — cria symlink public/storage
9. `permissions:set` — chown/chmod storage e bootstrap/cache
10. `deploy:symlink` — atualiza symlink current
11. `deploy:unlock` — libera lock
12. `apache:reload` — recarrega Apache

> **Importante:** NÃO usar `config:cache` — incompatível com PHP 8.5 (erro "A facade root has not been set").

### Comandos úteis na VPS

```bash
# Conectar via SSH
ssh -i ~/.ssh/colete_hostinger deploy@2.25.96.11

# Entrar no diretório do release atual
cd /var/www/html/cruzdeossos/current

# Colocar site em manutenção
php artisan down

# Tirar site de manutenção
php artisan up

# Limpar caches
php artisan optimize:clear

# Ver releases
ls -la /var/www/html/cruzdeossos/releases/
```

### Chaves SSH

| Chave | Acesso | Uso |
|-------|--------|-----|
| `~/.ssh/colete_hostinger` | root@2.25.96.11 e deploy@2.25.96.11 | Deployer + admin VPS |
| `/home/deploy/.ssh/github_cruzdeossos` (na VPS) | github.com | git pull na VPS (deploy key do repo) |

## Referência de Design

Inspirado no tema Lexrider (Motorcycle Club WordPress Theme) da ThemeForest:
https://themeforest.net/item/lexrider-motorcycle-club-woocommerce-wordpress-theme/23700786
