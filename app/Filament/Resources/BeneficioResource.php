<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BeneficioResource\Pages;
use App\Models\Beneficio;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Forms\Components\IconPickerModal;

class BeneficioResource extends Resource
{
    protected static ?string $model = Beneficio::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationLabel = 'Por Que Nós';
    protected static ?string $modelLabel = 'Benefício';
    protected static ?string $pluralModelLabel = 'Benefícios (Por Que Nós)';
    protected static ?string $navigationGroup = 'Site';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Card de benefício')
                    ->schema([
                        IconPickerModal::make('icone')
                            ->label('Ícone')
                            ->default('heroicon-o-star'),
                        Forms\Components\TextInput::make('titulo')
                            ->required()
                            ->label('Título')
                            ->maxLength(100),
                        Forms\Components\Textarea::make('descricao')
                            ->required()
                            ->label('Descrição')
                            ->rows(3),
                    ]),

                Forms\Components\Section::make('Configurações')
                    ->schema([
                        Forms\Components\Toggle::make('publicado')->default(true),
                        Forms\Components\TextInput::make('ordem')->numeric()->default(0),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('icone')->label('Ícone')->icon(fn ($state) => $state ?: 'heroicon-o-star'),
                Tables\Columns\TextColumn::make('titulo')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('descricao')->limit(50)->toggleable(),
                Tables\Columns\IconColumn::make('publicado')->boolean(),
                Tables\Columns\TextColumn::make('ordem')->sortable(),
            ])
            ->defaultSort('ordem')
            ->reorderable('ordem')
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBeneficios::route('/'),
            'create' => Pages\CreateBeneficio::route('/create'),
            'edit' => Pages\EditBeneficio::route('/{record}/edit'),
        ];
    }
}
