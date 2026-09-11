<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IntegranteResource\Pages;
use App\Models\Integrante;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class IntegranteResource extends Resource
{
    protected static ?string $model = Integrante::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Integrantes';
    protected static ?string $modelLabel = 'Integrante';
    protected static ?string $pluralModelLabel = 'Integrantes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('apelido')
                    ->required()
                    ->maxLength(100),
                Forms\Components\Select::make('cargo')
                    ->options([
                        'Presidente' => 'Presidente',
                        'Secretário' => 'Secretário',
                        'Sargente de Armas' => 'Sargente de Armas',
                        'Membro' => 'Membro',
                    ])
                    ->default('Membro')
                    ->required(),
                Forms\Components\FileUpload::make('foto')
                    ->image()
                    ->disk('public')
                    ->directory('integrantes')
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('3:4'),
                Forms\Components\Toggle::make('ativo')
                    ->default(true),
                Forms\Components\TextInput::make('ordem')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('foto')->circular()->disk('public'),
                Tables\Columns\TextColumn::make('apelido')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('cargo')->badge()->color('danger'),
                Tables\Columns\IconColumn::make('ativo')->boolean(),
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
            'index' => Pages\ListIntegrantes::route('/'),
            'create' => Pages\CreateIntegrante::route('/create'),
            'edit' => Pages\EditIntegrante::route('/{record}/edit'),
        ];
    }
}
