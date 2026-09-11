<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EvolucaoPassoResource\Pages;
use App\Models\EvolucaoPasso;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EvolucaoPassoResource extends Resource
{
    protected static ?string $model = EvolucaoPasso::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';
    protected static ?string $navigationLabel = 'Evolução';
    protected static ?string $modelLabel = 'Passo da Evolução';
    protected static ?string $pluralModelLabel = 'Passos da Evolução';
    protected static ?string $navigationGroup = 'Site';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Conteúdo do passo')
                    ->schema([
                        Forms\Components\TextInput::make('numero')
                            ->label('Número (ex: 01, 02, 03)')
                            ->required()
                            ->maxLength(3),
                        Forms\Components\TextInput::make('tag')
                            ->label('Tag (ex: Ride, Prospect, Full Patch)')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('titulo')
                            ->label('Título')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\Textarea::make('descricao')
                            ->label('Descrição')
                            ->required()
                            ->rows(4),
                    ])->columns(2),

                Forms\Components\Section::make('Imagem')
                    ->schema([
                        Forms\Components\FileUpload::make('imagem')
                            ->label('Imagem do passo')
                            ->image()
                            ->disk('public')
                            ->directory('evolucao')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('1:1'),
                    ]),

                Forms\Components\Section::make('Configurações')
                    ->schema([
                        Forms\Components\Toggle::make('destacado')
                            ->label('Destacar (estilo Full Patch)')
                            ->helperText('Aplica o estilo destacado com borda vermelha.'),
                        Forms\Components\Toggle::make('publicado')
                            ->default(true),
                        Forms\Components\TextInput::make('ordem')
                            ->numeric()
                            ->default(0)
                            ->helperText('Ordem de exibição'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('numero')->label('Nº')->sortable(),
                Tables\Columns\TextColumn::make('tag')->badge()->color('danger'),
                Tables\Columns\TextColumn::make('titulo')->searchable()->sortable(),
                Tables\Columns\IconColumn::make('destacado')->boolean(),
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
            'index' => Pages\ListEvolucaoPassos::route('/'),
            'create' => Pages\CreateEvolucaoPasso::route('/create'),
            'edit' => Pages\EditEvolucaoPasso::route('/{record}/edit'),
        ];
    }
}
