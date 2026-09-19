<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GaleriaResource\Pages;
use App\Models\Galeria;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GaleriaResource extends Resource
{
    protected static ?string $model = Galeria::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Galeria';
    protected static ?string $modelLabel = 'Foto';
    protected static ?string $pluralModelLabel = 'Fotos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('titulo')
                    ->required()
                    ->maxLength(200),
                Forms\Components\FileUpload::make('imagem')
                    ->image()
                    ->disk('public')
                    ->directory('galeria')
                    ->required(),
                Forms\Components\Textarea::make('descricao')
                    ->rows(3),
                Forms\Components\TextInput::make('ordem')
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('publicado')
                    ->default(true),
                Forms\Components\Toggle::make('destaque')
                    ->label('Destaque na página inicial')
                    ->helperText('Quando ativo, a foto aparece na seção Galeria da página inicial.')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('imagem')->disk('public'),
                Tables\Columns\TextColumn::make('titulo')->searchable(),
                Tables\Columns\IconColumn::make('publicado')->boolean(),
                Tables\Columns\IconColumn::make('destaque')->boolean(),
                Tables\Columns\TextColumn::make('ordem')->sortable(),
            ])
            ->defaultSort('ordem')
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGalerias::route('/'),
            'create' => Pages\CreateGaleria::route('/create'),
            'edit' => Pages\EditGaleria::route('/{record}/edit'),
        ];
    }
}
