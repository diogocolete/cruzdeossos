<?php

namespace App\Filament\Pages;

use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class MeuPerfil extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationLabel = 'Meu Perfil';
    protected static ?string $navigationGroup = 'Irmandade';
    protected static ?int $navigationSort = 0;

    protected static string $view = 'filament.pages.meu-perfil';

    public ?array $data = [];

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->integrante_id !== null;
    }

    public function mount(): void
    {
        $integrante = auth()->user()->integrante;

        abort_unless($integrante, 403, 'Seu usuário não está vinculado a um integrante.');

        $this->form->fill([
            'apelido' => $integrante->apelido,
            'slug'    => $integrante->slug ?? $integrante->slugPublico(),
            'foto'    => $integrante->foto,
            'bio'     => $integrante->bio,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Perfil público')
                    ->description('Estas informações aparecem na sua página pública no site.')
                    ->schema([
                        Forms\Components\TextInput::make('apelido')
                            ->label('Apelido')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug (URL)')
                            ->required()
                            ->maxLength(100)
                            ->unique('integrantes', 'slug', ignorable: fn () => auth()->user()->integrante)
                            ->helperText('Sua página pública: /integrantes/{slug}'),
                        Forms\Components\FileUpload::make('foto')
                            ->label('Foto')
                            ->image()
                            ->disk('public')
                            ->directory('integrantes')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('3:4'),
                        Forms\Components\Textarea::make('bio')
                            ->label('Bio')
                            ->rows(6)
                            ->maxLength(2000)
                            ->helperText('Conte um pouco sobre você — aparece na sua página pública.'),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')->label('Salvar perfil')->submit('save'),
            Action::make('ver_pagina')
                ->label('Ver minha página')
                ->icon('heroicon-o-globe-alt')
                ->color('gray')
                ->url(fn () => route('integrantes.show', $this->data['slug'] ?? ''))
                ->openUrlInNewTab(),
        ];
    }

    public function save(): void
    {
        $integrante = auth()->user()->integrante;
        abort_unless($integrante, 403);

        $integrante->update($this->form->getState());

        Notification::make()
            ->title('Perfil atualizado')
            ->body('Sua página pública foi atualizada.')
            ->status('success')
            ->send();
    }
}
