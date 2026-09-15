<?php

namespace App\Filament\Resources\FichaResource\Pages;

use App\Filament\Resources\FichaResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFicha extends ViewRecord
{
    protected static string $resource = FichaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('pdf')
                ->label('Ver PDF')
                ->icon('heroicon-o-document-text')
                ->modalContent(fn () => view('filament.ficha-pdf-modal', [
                    'url'    => route('admin.ficha.pdf', $this->record),
                    'titulo' => 'Ficha — ' . ($this->record->integrante?->apelido ?? ''),
                ]))
                ->modalHeading(fn () => 'Ficha — ' . ($this->record->integrante?->apelido ?? '') . ' (Rev. ' . $this->record->revisao . ')')
                ->modalWidth(\Filament\Support\Enums\MaxWidth::SevenExtraLarge)
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Fechar'),
            Actions\EditAction::make(),
        ];
    }
}
