<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepoimentoResource\Pages;
use App\Models\Depoimento;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DepoimentoResource extends Resource
{
    protected static ?string $model = Depoimento::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationLabel = 'Depoimentos';
    protected static ?string $modelLabel = 'Depoimento';
    protected static ?string $pluralModelLabel = 'Depoimentos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('autor')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('cargo')
                    ->default('Membro')
                    ->maxLength(100),
                Forms\Components\Textarea::make('texto')
                    ->required()
                    ->rows(4),
                Forms\Components\TextInput::make('ordem')
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('publicado')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('autor')->searchable(),
                Tables\Columns\TextColumn::make('cargo')->badge()->color('danger'),
                Tables\Columns\IconColumn::make('publicado')->boolean(),
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
            'index' => Pages\ListDepoimentos::route('/'),
            'create' => Pages\CreateDepoimento::route('/create'),
            'edit' => Pages\EditDepoimento::route('/{record}/edit'),
        ];
    }
}
