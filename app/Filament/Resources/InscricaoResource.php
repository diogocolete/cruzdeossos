<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InscricaoResource\Pages;
use App\Models\Inscricao;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InscricaoResource extends Resource
{
    protected static ?string $model = Inscricao::class;

    protected static ?string $slug = 'inscricoes';
    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';
    protected static ?string $navigationLabel = 'Inscrições (Junte-se)';
    protected static ?string $modelLabel = 'Inscrição';
    protected static ?string $pluralModelLabel = 'Inscrições';
    protected static ?string $navigationGroup = 'Site';
    protected static ?int $navigationSort = 3;

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();

        return (bool) $user && ($user->isDiretoria() || $user->can('view inscricoes'));
    }

    public static function canViewAny(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function canCreate(): bool
    {
        return false; // inscrições chegam pelo formulário público
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();

        return (bool) $user && ($user->isDiretoria() || $user->can('edit inscricoes'));
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->isDiretoria() ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Dados do candidato')
                    ->schema([
                        Forms\Components\TextInput::make('nome_completo')->label('Nome completo')->disabled(),
                        Forms\Components\TextInput::make('rede_social')->label('Rede social')->disabled(),
                        Forms\Components\TextInput::make('email')->label('E-mail')->disabled(),
                        Forms\Components\TextInput::make('telefone')->label('Telefone')->disabled(),
                        Forms\Components\TextInput::make('whatsapp')->label('WhatsApp')->disabled(),
                        Forms\Components\TextInput::make('endereco')->label('Endereço')->disabled(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Triagem')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options(Inscricao::STATUS)
                            ->required(),
                        Forms\Components\Textarea::make('notas')
                            ->label('Notas internas')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nome_completo')->label('Nome')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('whatsapp')->label('WhatsApp')->searchable(),
                Tables\Columns\TextColumn::make('email')->label('E-mail')->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => Inscricao::STATUS[$state] ?? $state)
                    ->color(fn ($state) => match ($state) {
                        'novo'       => 'danger',
                        'em_contato' => 'warning',
                        'aprovado'   => 'success',
                        default      => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')->label('Recebida em')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(Inscricao::STATUS),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Triagem'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => auth()->user()?->isDiretoria()),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInscricoes::route('/'),
            'edit'  => Pages\EditInscricao::route('/{record}/edit'),
        ];
    }
}
