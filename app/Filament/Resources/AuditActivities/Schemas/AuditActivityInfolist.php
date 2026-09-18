<?php

namespace App\Filament\Resources\AuditActivities\Schemas;

use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AuditActivityInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations générales')
                ->icon('heroicon-o-information-circle')
                ->schema([
                    Grid::make(3)->schema([
                        TextEntry::make('created_at')
                            ->label('Date & Heure')
                            ->dateTime('d/m/Y à H:i:s'),
                        TextEntry::make('log_name')
                            ->label('Profil')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'Livreur'         => 'info',
                                'Fournisseur'     => 'warning',
                                'Client'          => 'success',
                                'Commande Client' => 'primary',
                                default           => 'gray',
                            }),
                        TextEntry::make('description')
                            ->label('Action effectuée'),
                    ]),
                ]),
            // Section 2 : Qui a effectué l'action
            Section::make('Effectué par')
                ->icon('heroicon-o-user')
                ->schema([
                    Grid::make(2)->schema([
                        TextEntry::make('causer_name')
                            ->label('Utilisateur')
                            ->formatStateUsing(function ($record) {
                                if (!$record->causer) return 'Système';
                                return match ($record->causer_type) {
                                    'App\Models\Livreur'     => $record->causer->nom,
                                    'App\Models\Fournisseur' => $record->causer->nom,
                                    'App\Models\Client'      => $record->causer->nom,
                                    'App\Models\User'        => $record->causer->name,
                                    default                  => class_basename($record->causer_type) . " #{$record->causer_id}",
                                };
                            }),
                        TextEntry::make('causer_type')
                            ->label('Type')
                            ->formatStateUsing(fn ($state) => class_basename($state)),
                    ]),
                ]),
            // Section 3 : Élément modifié (subject)
            Section::make('Élément concerné')
                ->icon('heroicon-o-document-text')
                ->schema([
                    Grid::make(2)->schema([
                        TextEntry::make('subject_name')
                            ->label('Nom')
                            ->formatStateUsing(function ($record) {
                                if (!$record->subject) return '—';
                                return match ($record->subject_type) {
                                    'App\Models\Livreur'        => $record->subject->nom,
                                    'App\Models\Fournisseur'    => $record->subject->nom,
                                    'App\Models\Client'         => $record->subject->nom,
                                    'App\Models\CommandeClient' => 'Commande #' . $record->subject->id,
                                    'App\Models\User'           => $record->subject->name,
                                    default                     => '—',
                                };
                            }),
                        TextEntry::make('subject_type')
                            ->label('Type d\'élément')
                            ->formatStateUsing(fn ($state) => class_basename($state ?? '—')),
                    ]),
                ]),
            // Section 4 : Anciennes valeurs
            Section::make('Anciennes valeurs')
                ->icon('heroicon-o-arrow-uturn-left')
                ->collapsed()
                ->visible(fn ($record) => !empty($record->properties['old']))
                ->schema([
                    KeyValueEntry::make('properties.old')
                        ->label('')
                        ->keyLabel('Champ')
                        ->valueLabel('Ancienne valeur'),
                ]),
            // Section 5 : Nouvelles valeurs
            Section::make('Nouvelles valeurs')
                ->icon('heroicon-o-arrow-right')
                ->collapsed()
                ->visible(fn ($record) => !empty($record->properties['attributes']))
                ->schema([
                    KeyValueEntry::make('properties.attributes')
                        ->label('')
                        ->keyLabel('Champ')
                        ->valueLabel('Nouvelle valeur'),
                ]),
            // Section 6 : Métadonnées (IP, Session)
            Section::make('Métadonnées')
                ->icon('heroicon-o-server')
                ->collapsed()
                ->schema([
                    Grid::make(2)->schema([
                        TextEntry::make('properties.ip_address')
                            ->label('Adresse IP')
                            ->default('—'),
                        TextEntry::make('properties.session_id')
                            ->label('Session ID')
                            ->default('—')
                            ->copyable(),
                    ]),
                ]),
            ]);
    }
}
