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

## Comandos

- **Servir o draft local:** `cd draft && python3 -m http.server 8123`
- **Iniciar ngrok:** `cd draft && ./start-ngrok.sh`
- **Painel ngrok:** `http://localhost:4040`

## Produção

### Tecnologias em Produção

| Tecnologia | Versão | Uso |
|------------|--------|-----|
| Laravel | 11 | Framework PHP (aplicação principal) |
| PHP | 8.3 | Runtime |
| Filament | 3 | Painel administrativo |
| Spatie Laravel Permission | — | Controle de papéis e permissões |
| Intervention Image | — | Processamento de imagens (uploads) |
| MySQL/MariaDB | — | Banco de dados |
| Apache | 2 | Servidor web |
| Let's Encrypt / Certbot | — | Certificado SSL |
| Cloudflare | — | DNS e proxy (modo Full) |
| GitHub Webhook | — | Deploy automático (gratuito) |

### Acesso ao Servidor (VPS)

| Item | Valor |
|------|-------|
| Hostname | `vps66629.publiccloud.com.br` |
| IP | `191.252.103.193` |
| Usuário SSH | `deploy` |
| Usuário root | `root` |
| Caminho do projeto | `/var/www/html/cruzdeossos` |
| Document root do Apache | `/var/www/html/cruzdeossos/public` |
| Grupo web | `www-data` |

**Credenciais:** As senhas de SSH e banco de dados NÃO estão neste arquivo (evitar commit de segredos). Consulte o administrador do sistema ou o gerenciador de senhas da equipe.

### Domínios

| Domínio | Status |
|--------|--------|
| `https://cruzdeossos.com.br` | Ativo (SSL Let's Encrypt) |
| `https://www.cruzdeossos.com.br` | Ativo (SSL Let's Encrypt) |

### Painel Administrativo

| Item | Valor |
|------|-------|
| URL | `https://www.cruzdeossos.com.br/admin` |
| Usuário admin | `admin@cruzdeossos.com.br` |
| Senha | Consulte o administrador (não commitada) |

### Deploy

O deploy é automático via **GitHub Webhook** (gratuito, sem GitHub Actions):

1. `git push origin main` no repositório `https://github.com/diogocolete/cruzdeossos`
2. GitHub envia webhook para `https://cruzdeossos.com.br/webhook-deploy.php`
3. O webhook valida a assinatura HMAC e executa `deploy.sh`
4. `deploy.sh` roda: `git pull` → `composer install` → `migrate --force` → limpa caches → recria caches → ajusta permissões → `sudo systemctl reload apache2`

### Comandos úteis na VPS

```bash
# Conectar via SSH
ssh deploy@vps66629.publiccloud.com.br

# Entrar no diretório do projeto
cd /var/www/html/cruzdeossos

# Deploy manual (se o webhook falhar)
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear
php artisan config:cache && php artisan route:cache && php artisan view:cache
chown -R deploy:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
sudo systemctl reload apache2

# Colocar site em manutenção
php artisan down

# Tirar site de manutenção
php artisan up

# Limpar caches
php artisan optimize:clear
```

### Estrutura de Diretórios na VPS

```
/var/www/html/cruzdeossos/        # raiz do projeto Laravel
├── public/                       # document root do Apache
├── storage/app/public/galeria/   # fotos da galeria (477 imagens)
├── storage/app/public/integrantes/  # fotos dos integrantes
├── .env                          # configurações de produção (NÃO commitado)
├── webhook-deploy.php            # receiver do webhook do GitHub
└── deploy.sh                     # script de deploy
```

## Referência de Design

Inspirado no tema Lexrider (Motorcycle Club WordPress Theme) da ThemeForest:
https://themeforest.net/item/lexrider-motorcycle-club-woocommerce-wordpress-theme/23700786
