<?php

namespace App\Filament\Resources\AuditActivities\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AuditActivitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
               TextColumn::make('created_at')    // ← Plus besoin de Tables\Columns\TextColumn
                    ->label('Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
               
                TextColumn::make('causer_id')
                    ->label('Effectué par')
                    ->formatStateUsing(function ($record) {
                        if (!$record->causer) return 'Système';
                        return match ($record->causer_type) {
                            'App\Models\Livreur'     => $record->causer->nom,
                            'App\Models\Fournisseur' => $record->causer->nom,
                            'App\Models\Client'      =>$record->causer->nom,
                            'App\Models\User'        => $record->causer->name,
                            default                  => class_basename($record->causer_type) . " #{$record->causer_id}",
                        };
                    })
                   ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function (Builder $q) use ($search) {
                            // Recherche dans les Livreurs
                            $q->orWhereHasMorph(
                                'causer',
                                'App\Models\Livreur',
                                fn (Builder $q) => $q->where('nom', 'like', "%{$search}%")
                            )
                            // Recherche dans les Fournisseurs
                            ->orWhereHasMorph(
                                'causer',
                                'App\Models\Fournisseur',
                                fn (Builder $q) => $q->where('nom', 'like', "%{$search}%")
                            )
                            // Recherche dans les Clients
                            ->orWhereHasMorph(
                                'causer',
                                'App\Models\Client',
                                fn (Builder $q) => $q->where('nom', 'like', "%{$search}%")
                            )
                            // Recherche dans les Users
                            ->orWhereHasMorph(
                                'causer',
                                'App\Models\User',
                                fn (Builder $q) => $q->where('name', 'like', "%{$search}%")
                            );
                        });
                    }),

                TextColumn::make('causer_type')
                            ->label('Type')
                            ->formatStateUsing(fn ($state) => class_basename($state))
                    ->icon('heroicon-m-user') 
                    ->weight('bold'),

                TextColumn::make('subject_id')
                    ->label('Élément modifié')
                    ->formatStateUsing(function ($record) {
                        if (!$record->subject) return '—';
                        return match ($record->subject_type) {
                            'App\Models\Livreur'        => $record->subject->nom,
                            'App\Models\Fournisseur'    => $record->subject->nom,
                            'App\Models\Client'         =>  $record->subject->nom,
                            'App\Models\CommandeClient' => 'Commande #' . $record->subject->id,
                            'App\Models\User'           => $record->subject->name,
                            default                     => '—',
                        };
                    })
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->where(function (Builder $q) use ($search) {
                            $q->orWhereHasMorph(
                                'subject',
                                'App\Models\Livreur',
                                fn ($q) => $q->where('nom', 'like', "%{$search}%")
                            )
                            ->orWhereHasMorph(
                                'subject',
                                'App\Models\Fournisseur',
                                fn ($q) => $q->where('nom', 'like', "%{$search}%")
                            )
                            ->orWhereHasMorph(
                                'subject',
                                'App\Models\Client',
                                fn ($q) => $q->where('nom', 'like', "%{$search}%")
                            )
                            ->orWhereHasMorph(
                                'subject',
                                'App\Models\CommandeClient',
                                fn ($q) => $q->where('id', 'like', "%{$search}%")
                            );
                        });
                    }),
                TextColumn::make('log_name')
                    ->label('Profil')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Livreur'     => 'info',
                        'Fournisseur' => 'warning',
                        'Client'      => 'success',
                        default       => 'gray',
                    }),
                TextColumn::make('description')
                    ->label('Action')
                    ->wrap(),
            
                // TextColumn::make('created_at')
                //     ->dateTime()
                //     ->sortable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // SelectFilter::make('log_name')
                //     ->label('Type')
                //     ->options([
                //         'Livreur'         => 'Livreurs',
                //         'Fournisseur'     => 'Fournisseurs',
                //         'Client'          => 'Clients',
                //         'Commande Client' => 'Commandes',
                //     ]),
                 SelectFilter::make('causer_type')
                    ->label('Type ')
                    ->options([
                        \App\Models\User::class => 'Uniquement les administrateur',
                        \App\Models\Livreur::class => 'Uniquement les Livreurs',
                        \App\Models\Fournisseur::class => 'Uniquement les Fournisseurs',
                    ]),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('from')->label('Du'),
                        DatePicker::make('until')->label('Au'),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q) => $q->whereDate('created_at', '>=', $data['from']))
                            ->when($data['until'], fn ($q) => $q->whereDate('created_at', '<=', $data['until']));
                    }),
            ])
            ->recordActions([
                ViewAction::make(),
                // EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    // DeleteBulkAction::make(),
                ]),
            ]);
    }
}
