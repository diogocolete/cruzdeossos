<?php

namespace App\Filament\Resources\FichaResource\Pages;

use App\Filament\Resources\FichaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFicha extends CreateRecord
{
    protected static string $resource = FichaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['revisao'] = 1;

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->record->registrarRevisao(auth()->id(), 'Criação da ficha');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
