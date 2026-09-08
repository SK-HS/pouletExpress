<?php

namespace App\Filament\Resources\FournisseurSoldes;

use App\Filament\Resources\FournisseurSoldes\Pages\ManageFournisseurSoldes;
use App\Models\FournisseurSolde;
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
class FournisseurSoldeResource extends Resource
{
    protected static ?string $model = FournisseurSolde::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'GESTION SOLDE';
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'solde';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextInput::make('commande_client_id')
                //     ->numeric(),
                Select::make('commande_client_id')
                    ->relationship('commandeClient', 'id')
                     ->getOptionLabelFromRecordUsing(
                            fn ($record) => "{$record->reference}- ({$record->quartier?->nom_quartier})"
                        )
                    ->default(request()->query('commande_client_id'))
                    ->label('Commande')
                    ->disabled() 
                   ->dehydrated()
                    ->required(),
                Select::make('fournisseur_id')
                    ->relationship('fournisseur', 'id')
                     ->getOptionLabelFromRecordUsing(
                            fn ($record) => "{$record->nom}-{$record->quartier?->nom_quartier} - ({$record->telephone})"
                        )
                    ->default(request()->query('fournisseur_id'))
                    ->label('Fournisseur')
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
                TextEntry::make('commandeClient.reference')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('fournisseur.nom')
                    ->numeric()
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
                TextColumn::make('commandeClient.reference')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('fournisseur.nom')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('montant')
                    ->numeric()
                    ->suffix(' FCFA')
                    ->sortable(),
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

                     BulkAction::make('valideCommandeFournisseur')
                        ->label('Valider la Commande Fournisseur')
                        ->icon('heroicon-m-arrow-down-tray')
                        ->color('info')
                        ->modalHeading('Valider les Fournisseur sélectionnées')
                        ->requiresConfirmation()
                       ->modalWidth(Width::ExtraLarge)
                        
                        ->action(function (Collection $records, array $data) {
                            try {
                                // Appel de la logique métier située dans le Model Livreur
                                foreach ($records as $record) {
                                    $record->valider_montant_commande($data);
                                }

                                Notification::make()
                                    ->title('Commande mise jour avec succès')
                                    ->body(count($records) . ' Commande(s) Fournisseur à été mise jour avec succès.')
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
            'index' => ManageFournisseurSoldes::route('/'),
        ];
    }
}
