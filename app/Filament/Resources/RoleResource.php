<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Role;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?string $navigationLabel = 'Papéis e Permissões';
    protected static ?string $modelLabel = 'Papel';
    protected static ?string $pluralModelLabel = 'Papéis';
    protected static ?string $navigationGroup = 'Sistema';
    protected static ?int $navigationSort = 98;

    /**
     * Apenas Presidente e Secretário gerenciam papéis/permissões.
     */
    public static function canAccess(): bool
    {
        return auth()->user()?->isDiretoria() ?? false;
    }

    public static function getEloquentQuery(): Builder
    {
        // Presidente tem acesso total por definição — não é editável aqui
        return parent::getEloquentQuery()->where('name', '!=', 'Presidente');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Papel')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome do papel')
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true)
                            ->disabled(fn ($record) => in_array($record?->name, ['Secretário', 'Sargente de Armas', 'Membro'])),
                    ]),

                Forms\Components\Section::make('Permissões')
                    ->description('Marque o que este papel pode fazer no painel administrativo.')
                    ->schema([
                        Forms\Components\CheckboxList::make('permissions')
                            ->label('')
                            ->relationship('permissions', 'name')
                            ->columns(4)
                            ->bulkToggleable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Papel')->badge()->color('danger')->searchable(),
                Tables\Columns\TextColumn::make('permissions_count')->counts('permissions')->label('Permissões'),
                Tables\Columns\TextColumn::make('users_count')->counts('users')->label('Usuários'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit'   => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
