<?php

namespace App\Filament\Resources\DepoimentoResource\Pages;

use App\Filament\Resources\DepoimentoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDepoimentos extends ListRecords
{
    protected static string $resource = DepoimentoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
