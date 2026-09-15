<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use App\Jobs\PublicarPostRedesSociais;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('ver_no_site')
                ->label('Ver no site')
                ->icon('heroicon-o-globe-alt')
                ->url(fn () => route('posts.show', $this->record->slug))
                ->openUrlInNewTab()
                ->visible(fn () => $this->record->publicado),
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $this->record->registrarCompartilhamentos(auth()->id());
        PublicarPostRedesSociais::dispatch($this->record);
    }
}
