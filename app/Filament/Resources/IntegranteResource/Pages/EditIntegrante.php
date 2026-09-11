<?php

namespace App\Filament\Resources\IntegranteResource\Pages;

use App\Filament\Resources\IntegranteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIntegrante extends EditRecord
{
    protected static string $resource = IntegranteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
