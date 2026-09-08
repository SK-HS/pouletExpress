<?php

namespace App\Filament\Resources\CommandeLivreurs;

use App\Filament\Resources\CommandeLivreurs\Pages\ManageCommandeLivreurs;
use App\Models\CommandeLivreur;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class CommandeLivreurResource extends Resource
{
    protected static ?string $model = CommandeLivreur::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static string | UnitEnum | null $navigationGroup = 'GESTION COMMANDE';
    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'statut';

    public static function form(Schema $schema): Schema
    {
        return $schema
         
            ->components([
                Select::make('quartier')
                    ->relationship('quartier', 'nom_quartier')
                     ->getOptionLabelFromRecordUsing(
                            fn ($record) => "{$record->commune->ville?->nom_ville}-{$record->commune?->nom_commune} - {$record->nom_quartier}"
                        )
                    ->label('Quartier')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('distance')
                    ->required(),
                Select::make('statut')
                    ->options([
                        "EN_ATTENTE"=>"En attente d'un Livreur",
                        "AFFECTEE"=>"Livreur assigné",
                        "RECUPEREE"=>"Commande recuperée",
                        "EN_ROUTE"=>"Livreur en route pour livrer",
                        "LIVREE"=>"Commande livrée au client",
                    ])
                    ->required(),
                DateTimePicker::make('date_affectation')
                    ->label('Date Affectation')
                    ->default(now()),
                DateTimePicker::make('date_depart')
                    ->label('Date Depart'),
                DateTimePicker::make('date_arrivee')
                    ->label('Date Arrivée'),
                Select::make('commande_id')
                    ->relationship('commandeClient', 'reference')
                     ->getOptionLabelFromRecordUsing(
                            fn ($record) => "{$record->reference}-{$record->client?->nom}-{$record->client?->telephone} - ({$record->montant_ttc})"
                        )
                    ->label('Commande')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('livreur_id')
                    ->relationship('livreur', 'nom')
                     ->getOptionLabelFromRecordUsing(
                            fn ($record) => "{$record->nom}-{$record->type} -{$record->quartier?->nom_quartier}-{$record->adresse} - ({$record->telephone})"
                        )
                    ->label('Livreur')
                    ->searchable()
                    ->preload(),
                Hidden::make('user_id')
                    ->default(fn () => Auth::id()),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('quartier.nom_quartier'),
                TextEntry::make('distance'),
                TextEntry::make('statut')
                        ->badge(),
                TextEntry::make('date_affectation')
                    ->dateTime(),
                TextEntry::make('date_depart')
                    ->dateTime(),
                TextEntry::make('date_arrivee')
                    ->dateTime(),
                TextEntry::make('commandeClient.reference')
                    ->label('Commande')
                    ->formatStateUsing(function ($state, $record) {
                        return "Reference : {$record->commandeClient->reference} -N Client : {$record->commandeClient->client?->telephone} - Montant : {$record->commandeClient->montant_ttc} FCFA";
                    })
                    ->placeholder('-'),
                TextEntry::make('livreur.nom')
                    ->label("Livreur")
                    ->placeholder('-'),
                TextEntry::make('user.name')
                    ->label('Utilisateur'),
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
            ->recordTitleAttribute('statut')
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                      ->since(),
                TextColumn::make('quartier.nom_quartier')
                    ->searchable(),
                TextColumn::make('distance')
                    ->searchable(),
                TextColumn::make('statut')
                    ->badge()
                    ->searchable(),
                TextColumn::make('date_affectation')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('date_depart')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('date_arrivee')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('commandeClient.reference')
                    ->label('Commande')
                    ->sortable(),
                TextColumn::make('livreur.nom')
                    ->label('Livreur')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Utilisateur')
                    ->sortable(),
                
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
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCommandeLivreurs::route('/'),
        ];
    }
}
