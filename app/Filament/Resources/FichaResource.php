<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FichaResource\Pages;
use App\Filament\Resources\FichaResource\RelationManagers;
use App\Models\Ficha;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FichaResource extends Resource
{
    protected static ?string $model = Ficha::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';
    protected static ?string $navigationLabel = 'Fichas dos Integrantes';
    protected static ?string $modelLabel = 'Ficha';
    protected static ?string $pluralModelLabel = 'Fichas';
    protected static ?string $navigationGroup = 'Irmandade';
    protected static ?int $navigationSort = 1;

    /**
     * Dados estritamente privados: membro comum só enxerga a própria ficha.
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()->with('integrante');
        $user = auth()->user();

        if ($user && ! $user->isDiretoria()) {
            $query->where('integrante_id', $user->integrante_id ?? -1);
        }

        return $query;
    }

    public static function podeVerFicha(Ficha $ficha): bool
    {
        $user = auth()->user();

        return (bool) $user && ($user->isDiretoria() || $ficha->integrante_id === $user->integrante_id);
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();

        return (bool) $user && ($user->isDiretoria() || $user->integrante_id);
    }

    public static function canView($record): bool
    {
        return static::podeVerFicha($record);
    }

    public static function canEdit($record): bool
    {
        return static::podeVerFicha($record);
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->isDiretoria() ?? false;
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();
        if (! $user) {
            return false;
        }

        if ($user->isDiretoria()) {
            return true;
        }

        // Membro comum só pode criar a própria ficha (uma por integrante)
        return $user->integrante_id && ! Ficha::where('integrante_id', $user->integrante_id)->exists();
    }

    public static function form(Form $form): Form
    {
        $user = auth()->user();

        return $form
            ->schema([
                Forms\Components\Section::make('Integrante')
                    ->description('O cargo exibido na ficha é herdado do cadastro do integrante — altere em Integrantes.')
                    ->schema([
                        Forms\Components\Select::make('integrante_id')
                            ->label('Integrante')
                            ->relationship('integrante', 'apelido')
                            ->default(fn () => $user?->integrante_id)
                            ->disabled(fn () => ! $user?->isDiretoria())
                            ->dehydrated()
                            ->required()
                            ->unique(ignoreRecord: true),
                    ]),

                Forms\Components\Section::make('Dados pessoais')
                    ->schema([
                        Forms\Components\TextInput::make('nome_completo')
                            ->label('Nome completo')
                            ->required()
                            ->maxLength(200),
                        Forms\Components\DatePicker::make('data_nascimento')
                            ->label('Data de nascimento')
                            ->displayFormat('d/m/Y')
                            ->native(false),
                        Forms\Components\TextInput::make('apelido')
                            ->label('Apelido')
                            ->maxLength(100),
                        Forms\Components\Select::make('tipo_sanguineo')
                            ->label('Tipo sanguíneo')
                            ->options(array_combine(Ficha::TIPOS_SANGUINEOS, Ficha::TIPOS_SANGUINEOS)),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Saúde')
                    ->schema([
                        Forms\Components\Textarea::make('alergias')->label('Alérgico a')->rows(2),
                        Forms\Components\Textarea::make('remedios')->label('Remédios em uso')->rows(2),
                        Forms\Components\Textarea::make('doencas')->label('Doenças')->rows(2),
                        Forms\Components\Textarea::make('cirurgias')->label('Cirurgias')->rows(2),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Endereço')
                    ->schema([
                        Forms\Components\TextInput::make('endereco_logradouro')
                            ->label('Rua e número')
                            ->maxLength(200)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('endereco_bairro')->label('Bairro')->maxLength(120),
                        Forms\Components\TextInput::make('endereco_cidade')->label('Cidade')->maxLength(120),
                        Forms\Components\TextInput::make('endereco_uf')->label('UF')->maxLength(2),
                        Forms\Components\TextInput::make('endereco_cep')->label('CEP')->maxLength(10)
                            ->mask('99999-999'),
                    ])
                    ->columns(4),

                Forms\Components\Section::make('Contatos de emergência')
                    ->schema([
                        Forms\Components\Repeater::make('contatos')
                            ->label('')
                            ->schema([
                                Forms\Components\TextInput::make('nome')->label('Nome')->required()->maxLength(150),
                                Forms\Components\TextInput::make('parentesco')->label('Parentesco')->maxLength(60),
                                Forms\Components\TextInput::make('telefone')->label('Telefone')->required()->maxLength(30),
                            ])
                            ->columns(3)
                            ->defaultItems(1)
                            ->addActionLabel('Adicionar contato'),
                    ]),

                Forms\Components\Section::make('Anexos')
                    ->description('Fotos de documentos (CNH, etc.) — visíveis apenas para diretoria e o próprio integrante.')
                    ->schema([
                        Forms\Components\FileUpload::make('anexos')
                            ->label('')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->disk('local')
                            ->directory('fichas/anexos')
                            ->maxFiles(10)
                            ->helperText('Armazenado em disco privado (fora do public).'),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Ficha do Integrante')
                    ->schema([
                        Infolists\Components\TextEntry::make('integrante.apelido')->label('Integrante')->badge()->color('danger'),
                        Infolists\Components\TextEntry::make('revisao')->label('Revisão atual')->badge(),
                        Infolists\Components\TextEntry::make('nome_completo')->label('Nome completo'),
                        Infolists\Components\TextEntry::make('data_nascimento')->label('Nascimento')->date('d/m/Y'),
                        Infolists\Components\TextEntry::make('apelido')->label('Apelido'),
                        Infolists\Components\TextEntry::make('integrante.cargo')->label('Cargo'),
                        Infolists\Components\TextEntry::make('tipo_sanguineo')->label('Tipo sanguíneo'),
                    ])
                    ->columns(4),

                Infolists\Components\Section::make('Saúde')
                    ->schema([
                        Infolists\Components\TextEntry::make('alergias')->label('Alérgico a')->placeholder('—'),
                        Infolists\Components\TextEntry::make('remedios')->label('Remédios')->placeholder('—'),
                        Infolists\Components\TextEntry::make('doencas')->label('Doenças')->placeholder('—'),
                        Infolists\Components\TextEntry::make('cirurgias')->label('Cirurgias')->placeholder('—'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Endereço')
                    ->schema([
                        Infolists\Components\TextEntry::make('endereco_logradouro')->label('Rua e número')->placeholder('—'),
                        Infolists\Components\TextEntry::make('endereco_bairro')->label('Bairro')->placeholder('—'),
                        Infolists\Components\TextEntry::make('endereco_cidade')->label('Cidade')->placeholder('—'),
                        Infolists\Components\TextEntry::make('endereco_uf')->label('UF')->placeholder('—'),
                        Infolists\Components\TextEntry::make('endereco_cep')->label('CEP')->placeholder('—'),
                    ])
                    ->columns(5),

                Infolists\Components\Section::make('Contatos de emergência')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('contatos')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('nome')->label('Nome'),
                                Infolists\Components\TextEntry::make('parentesco')->label('Parentesco'),
                                Infolists\Components\TextEntry::make('telefone')->label('Telefone'),
                            ])
                            ->columns(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('integrante.apelido')->label('Integrante')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('nome_completo')->label('Nome completo')->searchable(),
                Tables\Columns\TextColumn::make('integrante.cargo')->label('Cargo')->badge()->color('danger'),
                Tables\Columns\TextColumn::make('revisao')->label('Revisão')->badge()->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->label('Atualizada em')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-text')
                    ->modalContent(fn (Ficha $record) => view('filament.ficha-pdf-modal', [
                        'url'    => route('admin.ficha.pdf', $record),
                        'titulo' => 'Ficha — ' . ($record->integrante?->apelido ?? ''),
                    ]))
                    ->modalHeading(fn (Ficha $record) => 'Ficha — ' . ($record->integrante?->apelido ?? '') . ' (Rev. ' . $record->revisao . ')')
                    ->modalWidth(\Filament\Support\Enums\MaxWidth::SevenExtraLarge)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Fechar'),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\RevisoesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFichas::route('/'),
            'create' => Pages\CreateFicha::route('/create'),
            'view'   => Pages\ViewFicha::route('/{record}'),
            'edit'   => Pages\EditFicha::route('/{record}/edit'),
        ];
    }
}
