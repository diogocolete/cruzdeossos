<?php

namespace App\Filament\Pages;

use App\Models\Conteudo;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ConteudoSite extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Conteúdo do Site';
    protected static ?string $navigationGroup = 'Site';
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.conteudo-site';

    public ?array $data = [];

    public function mount(): void
    {
        $campos = self::todosCampos();
        $dados = [];
        foreach ($campos as $chave => $config) {
            $dados[$chave] = Conteudo::get($chave, $config['default'] ?? '');
        }
        $this->form->fill($dados);
    }

    public static function todosCampos(): array
    {
        return [
            // SOBRE
            'sobre_kicker' => ['label' => 'Sobre — Kicker', 'tipo' => 'text', 'default' => 'Quem somos', 'section' => 'Sobre / Quem somos'],
            'sobre_titulo' => ['label' => 'Sobre — Título', 'tipo' => 'text', 'default' => 'Cruz de Ossos', 'section' => 'Sobre / Quem somos'],
            'sobre_lead1' => ['label' => 'Sobre — Parágrafo 1', 'tipo' => 'textarea', 'default' => 'Rode conosco numa jornada pelo desconhecido. Explore novas fronteiras. Acorde pela manhã com a emoção de estar num lugar onde nunca esteve antes.', 'section' => 'Sobre / Quem somos'],
            'sobre_lead2' => ['label' => 'Sobre — Parágrafo 2', 'tipo' => 'textarea', 'default' => 'Mantemos o que amamos nas motos — a liberdade, a irmandade e a estrada aberta. Nossa irmandade incentiva o envolvimento da família num ambiente divertido.', 'section' => 'Sobre / Quem somos'],

            // POR QUE NÓS
            'por_que_nos_kicker' => ['label' => 'Por Que Nós — Kicker', 'tipo' => 'text', 'default' => 'Nossos benefícios', 'section' => 'Por Que Nós'],
            'por_que_nos_titulo' => ['label' => 'Por Que Nós — Título', 'tipo' => 'text', 'default' => 'Por Que Nós', 'section' => 'Por Que Nós'],

            // EVOLUÇÃO
            'evolucao_kicker' => ['label' => 'Evolução — Kicker', 'tipo' => 'text', 'default' => 'Faça parte da família', 'section' => 'Evolução na Irmandade'],
            'evolucao_titulo' => ['label' => 'Evolução — Título', 'tipo' => 'text', 'default' => 'Evolução na Irmandade', 'section' => 'Evolução na Irmandade'],
            'evolucao_subtitulo' => ['label' => 'Evolução — Subtítulo', 'tipo' => 'textarea', 'default' => 'Do primeiro passeio ao patch completo — conheça o caminho para se tornar um irmão da Cruz de Ossos.', 'section' => 'Evolução na Irmandade'],

            // CTA
            'cta_kicker' => ['label' => 'CTA — Kicker', 'tipo' => 'text', 'default' => 'Passeio definitivo', 'section' => 'CTA Banner'],
            'cta_titulo' => ['label' => 'CTA — Título', 'tipo' => 'text', 'default' => 'Mantenha a memória viva', 'section' => 'CTA Banner'],
            'cta_btn_texto' => ['label' => 'CTA — Texto do botão', 'tipo' => 'text', 'default' => 'Ver Galeria', 'section' => 'CTA Banner'],
            'cta_btn_link' => ['label' => 'CTA — Link do botão', 'tipo' => 'text', 'default' => '#gallery', 'section' => 'CTA Banner'],
            'cta_imagem' => ['label' => 'CTA — Imagem de fundo', 'tipo' => 'upload', 'default' => 'banners/banner-5-esboco.png', 'section' => 'CTA Banner'],

            // JUNTE-SE
            'junte_se_kicker' => ['label' => 'Junte-se — Kicker', 'tipo' => 'text', 'default' => 'Faça parte', 'section' => 'Junte-se ao Clube'],
            'junte_se_titulo' => ['label' => 'Junte-se — Título', 'tipo' => 'text', 'default' => 'Junte-se ao clube', 'section' => 'Junte-se ao Clube'],
            'junte_se_texto' => ['label' => 'Junte-se — Texto', 'tipo' => 'textarea', 'default' => 'A Cruz de Ossos é uma irmandade de estrada construída sobre lealdade, respeito e família. Quem quer entrar começa rodando conosco nos passeios como Ride — sem colete — para conhecer o grupo e mostrar compromisso. Preencha o formulário e a diretoria entrará em contato para apresentar a irmandade e combinar o primeiro encontro.', 'section' => 'Junte-se ao Clube'],
            'junte_se_btn' => ['label' => 'Junte-se — Texto do botão', 'tipo' => 'text', 'default' => 'Enviar inscrição', 'section' => 'Junte-se ao Clube'],
            'junte_se_imagem' => ['label' => 'Junte-se — Imagem', 'tipo' => 'upload', 'default' => 'junte-se/junte-se-moto.jpg', 'section' => 'Junte-se ao Clube', 'dir' => 'junte-se'],

            // CONTATO
            'contato_titulo' => ['label' => 'Contato — Título', 'tipo' => 'text', 'default' => 'Tem dúvidas? Não espere, vamos conversar', 'section' => 'Contato'],
            'contato_telefone' => ['label' => 'Contato — Telefone', 'tipo' => 'text', 'default' => '+55 (41) 99999-9999', 'section' => 'Contato'],
            'contato_email' => ['label' => 'Contato — E-mail', 'tipo' => 'text', 'default' => 'contato@cruzdeossos.com.br', 'section' => 'Contato'],
            'contato_endereco_titulo' => ['label' => 'Contato — Título do endereço', 'tipo' => 'text', 'default' => 'Sede da irmandade', 'section' => 'Contato'],
            'contato_endereco' => ['label' => 'Contato — Endereço', 'tipo' => 'text', 'default' => 'Curitiba, PR — Brasil', 'section' => 'Contato'],

            // REDES SOCIAIS
            'social_facebook' => ['label' => 'Facebook — URL', 'tipo' => 'text', 'default' => '', 'section' => 'Redes Sociais'],
            'social_instagram' => ['label' => 'Instagram — URL', 'tipo' => 'text', 'default' => '', 'section' => 'Redes Sociais'],
            'social_twitter' => ['label' => 'Twitter / X — URL', 'tipo' => 'text', 'default' => '', 'section' => 'Redes Sociais'],
            'social_youtube' => ['label' => 'YouTube — URL', 'tipo' => 'text', 'default' => '', 'section' => 'Redes Sociais'],

            // RODAPÉ
            'footer_descricao' => ['label' => 'Rodapé — Descrição', 'tipo' => 'textarea', 'default' => 'Cavaleiros da estrada, irmãos da cruz. Irmandade desde 22/03/2025.', 'section' => 'Rodapé'],
            'footer_telefone' => ['label' => 'Rodapé — Telefone', 'tipo' => 'text', 'default' => '+55 (41) 99999-9999', 'section' => 'Rodapé'],
            'footer_email' => ['label' => 'Rodapé — E-mail', 'tipo' => 'text', 'default' => 'contato@cruzdeossos.com.br', 'section' => 'Rodapé'],
            'footer_endereco' => ['label' => 'Rodapé — Endereço', 'tipo' => 'text', 'default' => 'Curitiba, PR', 'section' => 'Rodapé'],
        ];
    }

    public function form(Form $form): Form
    {
        $campos = self::todosCampos();
        $secoes = [];
        foreach ($campos as $chave => $config) {
            $secoes[$config['section']][$chave] = $config;
        }

        $schema = [];
        foreach ($secoes as $nomeSecao => $camposSecao) {
            $componentes = [];
            foreach ($camposSecao as $chave => $config) {
                if ($config['tipo'] === 'textarea') {
                    $componentes[] = Forms\Components\Textarea::make($chave)
                        ->label($config['label'])
                        ->rows(3)
                        ->default($config['default']);
                } elseif ($config['tipo'] === 'upload') {
                    $componentes[] = Forms\Components\FileUpload::make($chave)
                        ->label($config['label'])
                        ->image()
                        ->disk('public')
                        ->directory($config['dir'] ?? 'cta')
                        ->default($config['default']);
                } else {
                    $componentes[] = Forms\Components\TextInput::make($chave)
                        ->label($config['label'])
                        ->default($config['default']);
                }
            }
            $schema[] = Forms\Components\Section::make($nomeSecao)
                ->schema($componentes)
                ->columns(2);
        }

        return $form->schema($schema)->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')->label('Salvar conteúdo')->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();
        foreach (self::todosCampos() as $chave => $config) {
            Conteudo::set($chave, $data[$chave] ?? '');
        }

        Notification::make()
            ->title('Conteúdo do site atualizado')
            ->body('Todos os textos foram salvos e aplicados ao site.')
            ->status('success')
            ->send();
    }
}
