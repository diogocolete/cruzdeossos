<?php

namespace App\Filament\Pages;

use App\Models\Configuracao;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Configuracoes extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Configurações';
    protected static ?string $navigationGroup = 'Sistema';
    protected static ?int $navigationSort = 99;

    protected static string $view = 'filament.pages.configuracoes';

    public ?array $data = [];

    /** Seções do site que podem ser ativadas/desativadas */
    public static array $secoes = [
        'sec_sobre'        => 'Sobre / Quem somos',
        'sec_eventos'      => 'Eventos',
        'sec_por_que_nos'  => 'Por Que Nós',
        'sec_evolucao'     => 'Evolução na Irmandade',
        'sec_cta'          => 'Banner CTA',
        'sec_galeria'      => 'Galeria',
        'sec_integrantes'  => 'Integrantes',
        'sec_posts'        => 'Posts em Destaque',
        'sec_noticias'     => 'Notícias',
        'sec_depoimentos'  => 'Depoimentos',
        'sec_junte_se'     => 'Junte-se (página dedicada)',
        'sec_contato'      => 'Contato',
    ];

    /** Campos das integrações de redes sociais (chave => label) */
    public static array $integracoes = [
        'int_facebook_ativo'        => 'Facebook ativo',
        'int_facebook_page_id'      => 'Facebook — ID da Página',
        'int_facebook_token'        => 'Facebook — Token de acesso',
        'int_instagram_ativo'       => 'Instagram ativo',
        'int_instagram_account_id'  => 'Instagram — ID da conta (IG Business)',
        'int_instagram_token'       => 'Instagram — Token de acesso',
    ];

    public function mount(): void
    {
        $dados = [
            'manutencao_ativa' => Configuracao::get('manutencao_ativa', '0') === '1',
        ];

        foreach (array_keys(self::$secoes) as $chave) {
            $dados[$chave] = Configuracao::get($chave, '1') === '1';
        }

        foreach (array_keys(self::$integracoes) as $chave) {
            $valor = Configuracao::get($chave, '');
            $dados[$chave] = str_ends_with($chave, '_ativo') ? $valor === '1' : $valor;
        }

        $this->form->fill($dados);
    }

    public function form(Form $form): Form
    {
        $toggles = [];
        foreach (self::$secoes as $chave => $label) {
            $toggles[] = Forms\Components\Toggle::make($chave)
                ->label($label)
                ->onColor('success')
                ->offColor('danger');
        }

        return $form
            ->schema([
                Forms\Components\Section::make('Modo de Manutenção')
                    ->description('Quando ativado, o site público exibe uma tela de "em manutenção". Apenas usuários logados no painel admin continuam vendo o site normalmente.')
                    ->schema([
                        Forms\Components\Toggle::make('manutencao_ativa')
                            ->label('Site em manutenção')
                            ->helperText('Visitantes verão a página de manutenção. O painel admin continua acessível.')
                            ->onColor('success')
                            ->offColor('danger'),
                    ])
                    ->icon('heroicon-o-wrench-screwdriver'),

                Forms\Components\Section::make('Seções do Site')
                    ->description('Ative ou desative as seções que aparecem no site público. Seções desativadas não aparecem para os visitantes.')
                    ->schema($toggles)
                    ->columns(2)
                    ->icon('heroicon-o-rectangle-stack'),

                Forms\Components\Section::make('Integrações — Redes Sociais')
                    ->description('Controla o envio automático de posts para as redes. Um post só é compartilhado se a integração estiver ativa E configurada. Sem configuração, o compartilhamento fica registrado como "desativado" no log do post.')
                    ->schema([
                        Forms\Components\Toggle::make('int_facebook_ativo')
                            ->label(self::$integracoes['int_facebook_ativo'])
                            ->onColor('success')
                            ->offColor('danger'),
                        Forms\Components\Toggle::make('int_instagram_ativo')
                            ->label(self::$integracoes['int_instagram_ativo'])
                            ->onColor('success')
                            ->offColor('danger'),
                        Forms\Components\TextInput::make('int_facebook_page_id')
                            ->label(self::$integracoes['int_facebook_page_id'])
                            ->maxLength(100),
                        Forms\Components\TextInput::make('int_instagram_account_id')
                            ->label(self::$integracoes['int_instagram_account_id'])
                            ->maxLength(100),
                        Forms\Components\TextInput::make('int_facebook_token')
                            ->label(self::$integracoes['int_facebook_token'])
                            ->password()
                            ->revealable()
                            ->maxLength(500),
                        Forms\Components\TextInput::make('int_instagram_token')
                            ->label(self::$integracoes['int_instagram_token'])
                            ->password()
                            ->revealable()
                            ->maxLength(500),
                    ])
                    ->columns(2)
                    ->icon('heroicon-o-share')
                    ->collapsible(),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Salvar configurações')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Configuracao::set('manutencao_ativa', $data['manutencao_ativa'] ? '1' : '0');

        foreach (array_keys(self::$secoes) as $chave) {
            Configuracao::set($chave, ($data[$chave] ?? false) ? '1' : '0');
        }

        foreach (array_keys(self::$integracoes) as $chave) {
            $valor = $data[$chave] ?? '';
            if (str_ends_with($chave, '_ativo')) {
                $valor = $valor ? '1' : '0';
            }
            Configuracao::set($chave, (string) $valor);
        }

        Notification::make()
            ->title('Configurações salvas')
            ->body('As alterações foram aplicadas ao site público.')
            ->status('success')
            ->send();
    }
}
