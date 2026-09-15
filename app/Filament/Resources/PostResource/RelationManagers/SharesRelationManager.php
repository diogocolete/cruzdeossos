<?php

namespace App\Filament\Resources\PostResource\RelationManagers;

use App\Models\Post;
use App\Models\PostShare;
use App\Services\SocialPublisher;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SharesRelationManager extends RelationManager
{
    protected static string $relationship = 'shares';

    protected static ?string $title = 'Compartilhamento nas redes';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('rede')
                    ->label('Rede')
                    ->badge()
                    ->formatStateUsing(fn ($state) => Post::REDES_DISPONIVEIS[$state] ?? $state)
                    ->color('danger'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => PostShare::STATUS[$state] ?? $state)
                    ->color(fn ($state) => match ($state) {
                        'enviado'    => 'success',
                        'erro'       => 'danger',
                        'desativado' => 'gray',
                        default      => 'warning',
                    }),
                Tables\Columns\TextColumn::make('resposta')->label('Detalhe')->limit(60)->tooltip(fn ($record) => $record->resposta),
                Tables\Columns\TextColumn::make('updated_at')->label('Quando')->dateTime('d/m/Y H:i'),
            ])
            ->actions([
                Tables\Actions\Action::make('reenviar')
                    ->label('Reenviar')
                    ->icon('heroicon-o-arrow-path')
                    ->visible(fn (PostShare $record) => in_array($record->status, ['erro', 'desativado']))
                    ->action(function (PostShare $record) {
                        $record->update(['status' => 'pendente', 'resposta' => null]);
                        app(SocialPublisher::class)->publicar($record);

                        Notification::make()
                            ->title('Compartilhamento reprocessado')
                            ->body('Status: ' . (PostShare::STATUS[$record->fresh()->status] ?? $record->status))
                            ->send();
                    }),
            ])
            ->emptyStateHeading('Sem compartilhamentos')
            ->emptyStateDescription('Marque as redes sociais no formulário do post para compartilhar.');
    }
}
