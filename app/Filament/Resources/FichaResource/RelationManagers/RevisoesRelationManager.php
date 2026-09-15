<?php

namespace App\Filament\Resources\FichaResource\RelationManagers;

use App\Models\FichaRevision;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class RevisoesRelationManager extends RelationManager
{
    protected static string $relationship = 'revisions';

    protected static ?string $title = 'Histórico de revisões';

    public static function canViewForRecord($ownerRecord, string $pageClass): bool
    {
        return \App\Filament\Resources\FichaResource::podeVerFicha($ownerRecord);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('revisao')->label('Revisão')->badge()->color('danger'),
                Tables\Columns\TextColumn::make('created_at')->label('Data')->dateTime('d/m/Y H:i'),
                Tables\Columns\TextColumn::make('user.name')->label('Alterado por')->placeholder('—'),
                Tables\Columns\TextColumn::make('resumo')->label('Resumo')->placeholder('—'),
            ])
            ->defaultSort('revisao', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Ver'),
                Tables\Actions\Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-text')
                    ->modalContent(fn (FichaRevision $record) => view('filament.ficha-pdf-modal', [
                        'url'    => route('admin.ficha.revisao.pdf', $record),
                        'titulo' => 'Ficha rev. ' . $record->revisao,
                    ]))
                    ->modalHeading(fn (FichaRevision $record) => 'Ficha — Revisão ' . $record->revisao . ' (' . $record->created_at->format('d/m/Y H:i') . ')')
                    ->modalWidth(\Filament\Support\Enums\MaxWidth::SevenExtraLarge)
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Fechar'),
            ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Dados desta revisão')
                    ->schema([
                        Infolists\Components\TextEntry::make('dados.nome_completo')->label('Nome completo'),
                        Infolists\Components\TextEntry::make('dados.data_nascimento')
                            ->label('Nascimento')
                            ->formatStateUsing(fn ($state) => $state ? \Carbon\Carbon::parse($state)->format('d/m/Y') : '—'),
                        Infolists\Components\TextEntry::make('dados.apelido')->label('Apelido'),
                        Infolists\Components\TextEntry::make('dados.cargo')->label('Cargo'),
                        Infolists\Components\TextEntry::make('dados.tipo_sanguineo')->label('Tipo sanguíneo'),
                    ])
                    ->columns(3),
                Infolists\Components\Section::make('Saúde')
                    ->schema([
                        Infolists\Components\TextEntry::make('dados.alergias')->label('Alérgico a')->placeholder('—'),
                        Infolists\Components\TextEntry::make('dados.remedios')->label('Remédios')->placeholder('—'),
                        Infolists\Components\TextEntry::make('dados.doencas')->label('Doenças')->placeholder('—'),
                        Infolists\Components\TextEntry::make('dados.cirurgias')->label('Cirurgias')->placeholder('—'),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make('Endereço e contatos')
                    ->schema([
                        Infolists\Components\TextEntry::make('dados.endereco_logradouro')->label('Endereço')->placeholder('—'),
                        Infolists\Components\TextEntry::make('dados.endereco_bairro')->label('Bairro')->placeholder('—'),
                        Infolists\Components\TextEntry::make('dados.endereco_cidade')->label('Cidade')->placeholder('—'),
                        Infolists\Components\TextEntry::make('dados.endereco_uf')->label('UF')->placeholder('—'),
                        Infolists\Components\TextEntry::make('dados.endereco_cep')->label('CEP')->placeholder('—'),
                        Infolists\Components\TextEntry::make('contatos_formatados')
                            ->label('Contatos')
                            ->state(fn (FichaRevision $record) => collect($record->dados['contatos'] ?? [])
                                ->map(fn ($c) => trim(($c['nome'] ?? '') . ' (' . ($c['parentesco'] ?? '') . '): ' . ($c['telefone'] ?? ''))
                                )->implode("\n"))
                            ->placeholder('—'),
                    ])
                    ->columns(3),
            ]);
    }
}
