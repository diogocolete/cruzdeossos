<?php

namespace App\Filament\Resources\EvolucaoPassoResource\Pages;

use App\Filament\Resources\EvolucaoPassoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEvolucaoPassos extends ListRecords
{
    protected static string $resource = EvolucaoPassoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
