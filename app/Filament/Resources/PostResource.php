<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $navigationLabel = 'Posts';
    protected static ?string $modelLabel = 'Post';
    protected static ?string $pluralModelLabel = 'Posts';
    protected static ?string $navigationGroup = 'Site';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        $user = auth()->user();

        return $form
            ->schema([
                Forms\Components\Section::make('Post')
                    ->schema([
                        Forms\Components\TextInput::make('titulo')
                            ->label('Título')
                            ->required()
                            ->maxLength(200)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', str($state)->slug())),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Usado na URL pública do post.'),
                        Forms\Components\Select::make('integrante_id')
                            ->label('Membro autor')
                            ->relationship('integrante', 'apelido')
                            ->default(fn () => $user?->integrante_id)
                            ->disabled(fn () => ! $user?->isDiretoria())
                            ->dehydrated()
                            ->required()
                            ->helperText(fn () => $user?->isDiretoria()
                                ? 'Diretoria pode publicar em nome de outro membro.'
                                : 'O post será publicado em seu nome.'),
                        Forms\Components\DateTimePicker::make('published_at')
                            ->label('Data de publicação')
                            ->default(now())
                            ->displayFormat('d/m/Y H:i')
                            ->required(),
                        Forms\Components\RichEditor::make('conteudo')
                            ->label('Texto')
                            ->toolbarButtons([
                                'bold', 'italic', 'underline', 'strike',
                                'h2', 'h3',
                                'bulletList', 'orderedList',
                                'link', 'blockquote',
                                'undo', 'redo',
                            ])
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Mídia')
                    ->schema([
                        Forms\Components\FileUpload::make('imagem')
                            ->label('Imagem de capa')
                            ->image()
                            ->disk('public')
                            ->directory('posts'),
                        Forms\Components\FileUpload::make('video')
                            ->label('Vídeo (arquivo)')
                            ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/ogg', 'video/quicktime'])
                            ->maxSize(204800)
                            ->disk('public')
                            ->directory('posts/videos')
                            ->helperText('MP4/WebM até ~200MB (limite do servidor). Prefira URL para vídeos grandes.'),
                        Forms\Components\TextInput::make('video_url')
                            ->label('URL de vídeo (YouTube/Vimeo)')
                            ->url()
                            ->maxLength(255)
                            ->helperText('Alternativa ao upload: cole o link do YouTube ou Vimeo.'),
                    ])
                    ->columns(3)
                    ->collapsible(),

                Forms\Components\Section::make('Publicação')
                    ->schema([
                        Forms\Components\Toggle::make('publicado')
                            ->label('Publicado')
                            ->default(true)
                            ->helperText('Desative para manter como rascunho.'),
                        Forms\Components\Toggle::make('destaque')
                            ->label('Destaque na home')
                            ->helperText('A home mostra até 6 posts: os em destaque primeiro.')
                            ->visible(fn () => $user?->isDiretoria())
                            ->dehydrated(fn () => (bool) $user?->isDiretoria()),
                        Forms\Components\CheckboxList::make('redes_sociais')
                            ->label('Compartilhar nas redes sociais')
                            ->options(Post::REDES_DISPONIVEIS)
                            ->columns(2)
                            ->helperText('O envio depende das integrações configuradas em Configurações → Integrações.'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('imagem')->disk('public')->label('Capa'),
                Tables\Columns\TextColumn::make('titulo')->label('Título')->searchable()->sortable()->limit(50),
                Tables\Columns\TextColumn::make('integrante.apelido')->label('Membro')->sortable(),
                Tables\Columns\TextColumn::make('published_at')->label('Publicado em')->dateTime('d/m/Y H:i')->sortable(),
                Tables\Columns\IconColumn::make('destaque')->boolean()->label('Destaque'),
                Tables\Columns\IconColumn::make('publicado')->boolean(),
            ])
            ->defaultSort('published_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        // Membro comum só enxerga os próprios posts
        if ($user && ! $user->isDiretoria()) {
            $query->where('integrante_id', $user->integrante_id);
        }

        return $query;
    }

    public static function shouldRegisterNavigation(): bool
    {
        $user = auth()->user();

        return (bool) $user && ($user->isDiretoria() || $user->integrante_id);
    }

    public static function canCreate(): bool
    {
        $user = auth()->user();

        return (bool) $user && ($user->isDiretoria() || $user->integrante_id);
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();

        return $user?->isDiretoria() || $record->integrante_id === $user?->integrante_id;
    }

    public static function canDelete($record): bool
    {
        return static::canEdit($record);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\SharesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit'   => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
