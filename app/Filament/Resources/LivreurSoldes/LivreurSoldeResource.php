<?php

namespace App\Filament\Resources\LivreurSoldes;

use App\Filament\Resources\LivreurSoldes\Pages\ManageLivreurSoldes;
use App\Models\LivreurSolde;
use BackedEnum;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use UnitEnum;


class LivreurSoldeResource extends Resource
{
    protected static ?string $model = LivreurSolde::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'GESTION SOLDE';
    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'solde';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('commande_livreur_id')
                    ->relationship('commandeLivreur', 'id')
                     ->getOptionLabelFromRecordUsing(
                            fn ($record) => "{$record->commandeClient?->reference}- ({$record->quartier?->nom_quartier})"
                        )
                    ->default(request()->query('commande_livreur_id'))
                    ->label('Commande')
                    ->disabled() 
                   ->dehydrated()
                    ->required(),

                Select::make('livreur_id')
                    ->relationship('livreur', 'id')
                     ->getOptionLabelFromRecordUsing(
                            fn ($record) => "{$record->nom}-{$record->quartier?->nom_quartier} - ({$record->telephone})"
                        )
                    ->default(request()->query('livreur_id'))
                    ->label('Livreur')
                    ->disabled() 
                   ->dehydrated()
                    ->required(),

                TextInput::make('montant')
                    ->required()
                    ->numeric(),
                Select::make('statut')
                    ->options([
            'EN_ATTENTE' => 'EN_ATTENTE',
            'DISPONIBLE' => 'DISPONIBLE',
            // 'PAYE' => 'PAYE',
        ])
                    ->required(),
                DateTimePicker::make('disponible_le'),
                // DateTimePicker::make('paye_le'),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('commandeLivreur.commandeClient.reference')
                    ->placeholder('-'),
                TextEntry::make('livreur.nom')
                    ->placeholder('-'),
                TextEntry::make('montant')
                    ->numeric()
                    ->suffix(' FCFA'),
                TextEntry::make('statut')
                    ->badge(),
                TextEntry::make('disponible_le')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('paye_le')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('solde')
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('commandeLivreur.commandeClient.reference')
                    ->sortable(),
                TextColumn::make('livreur.nom')
                    ->sortable(),
                TextColumn::make('montant')
                    ->numeric()
                    ->sortable()
                    ->suffix(' FCFA'),
                TextColumn::make('statut')
                    ->badge(),
                TextColumn::make('disponible_le')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('paye_le')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                // BulkActionGroup::make([
                DeleteBulkAction::make(),

                BulkAction::make('valideLivraison')
                        ->label('Valider la Livraison')
                        ->icon('heroicon-m-arrow-down-tray')
                        ->color('info')
                        ->modalHeading('Valider les livraisons sélectionnées')
                        ->requiresConfirmation()
                       ->modalWidth(Width::ExtraLarge)
                        
                        ->action(function (Collection $records, array $data) {
                            try {
                                // Appel de la logique métier située dans le Model Livreur
                                foreach ($records as $record) {
                                    $record->valider_montant_livraison($data);
                                }

                                Notification::make()
                                    ->title('livraison mise jour avec succès')
                                    ->body(count($records) . ' livraison(s) à été mise jour avec succès.')
                                    ->success()
                                    ->send();

                            } catch (\Exception $e) {
                                Notification::make()
                                    ->title('Erreur lors de la mise à jour')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        })
            ->deselectRecordsAfterCompletion(),

    ]);
// ]);
             
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageLivreurSoldes::route('/'),
        ];
    }
}
