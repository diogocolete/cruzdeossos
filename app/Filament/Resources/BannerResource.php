<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Banners';
    protected static ?string $modelLabel = 'Banner';
    protected static ?string $pluralModelLabel = 'Banners';
    protected static ?string $navigationGroup = 'Site';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Conteúdo do banner')
                    ->schema([
                        Forms\Components\TextInput::make('kicker')
                            ->label('Texto superior (kicker)')
                            ->maxLength(100)
                            ->helperText('Ex: Cavaleiros da estrada'),
                        Forms\Components\TextInput::make('titulo')
                            ->required()
                            ->label('Título principal')
                            ->maxLength(200),
                        Forms\Components\TextInput::make('subtitulo')
                            ->label('Subtítulo (linha 2)')
                            ->maxLength(200),
                        Forms\Components\Textarea::make('texto')
                            ->label('Texto descritivo')
                            ->rows(2),
                    ])->columns(2),

                Forms\Components\Section::make('Imagem de fundo')
                    ->schema([
                        Forms\Components\FileUpload::make('imagem')
                            ->image()
                            ->disk('public')
                            ->directory('banners')
                            ->required()
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('3:2'),
                    ]),

                Forms\Components\Section::make('Botões')
                    ->schema([
                        Forms\Components\TextInput::make('btn1_texto')
                            ->label('Botão 1 — texto')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('btn1_link')
                            ->label('Botão 1 — link')
                            ->helperText('Ex: #about ou /noticia/titulo'),
                        Forms\Components\TextInput::make('btn2_texto')
                            ->label('Botão 2 — texto')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('btn2_link')
                            ->label('Botão 2 — link'),
                    ])->columns(2),

                Forms\Components\Section::make('Configurações')
                    ->schema([
                        Forms\Components\Toggle::make('publicado')
                            ->default(true),
                        Forms\Components\TextInput::make('ordem')
                            ->numeric()
                            ->default(0)
                            ->helperText('Ordem de exibição no slider'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('imagem')->label('Imagem')->disk('public'),
                Tables\Columns\TextColumn::make('titulo')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('kicker')->toggleable(),
                Tables\Columns\IconColumn::make('publicado')->boolean(),
                Tables\Columns\TextColumn::make('ordem')->sortable(),
            ])
            ->defaultSort('ordem')
            ->reorderable('ordem')
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
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}
