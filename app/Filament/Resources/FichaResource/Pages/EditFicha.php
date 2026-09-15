<?php

namespace App\Filament\Resources\FichaResource\Pages;

use App\Filament\Resources\FichaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFicha extends EditRecord
{
    protected static string $resource = FichaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
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
            Actions\DeleteAction::make()
                ->visible(fn () => auth()->user()?->isDiretoria()),
        ];
    }

    protected function afterSave(): void
    {
        // Nunca sobrescreve: cada edição gera uma nova revisão.
        $this->record->revisao = (int) $this->record->revisao + 1;
        $this->record->saveQuietly();
        $this->record->registrarRevisao(auth()->id());
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->record]);
    }
}
