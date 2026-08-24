<?php

namespace App\Filament\Resources\Livreurs;

use App\Filament\Resources\Livreurs\Pages\ManageLivreurs;
use App\Models\Livreur;
use BackedEnum;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\RepeatableEntry\TableColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class LivreurResource extends Resource
{
    protected static ?string $model = Livreur::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nom';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextInput::make('reference'),

                TextInput::make('nom')
                    ->required(),
                Select::make('type')
                    ->label('Type de livreur')
                    ->options([
                        'VELO' => 'Vélo',
                        'MOTO' => 'Moto',
                        'TRICYCLE' => 'Tricycle',
                        'VOITURE' => 'Voiture',
                        'CAMIONNETTE' => 'Camionnette',
                        'CAMION' => 'Camion',
                    ]),
                Select::make('categorie')
                    ->label('Categorie du livreur')
                    ->options([
                        'STANDARD' => 'STANDARD',
                        'VIP' => 'VIP',
                        'VVIP' => 'VVIP',
                    ])->default("STANDARD"),
                TextInput::make('adresse'),
                TextInput::make('telephone')
                    ->tel(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('contact'),
                Hidden::make('user_id')
                    ->default(fn () => Auth::id()),
                Select::make('quartier')
                    ->relationship('quartier', 'nom_quartier')
                     ->getOptionLabelFromRecordUsing(
                            fn ($record) => "{$record->commune?->ville?->nom_ville}-{$record->commune?->nom_commune} - {$record->nom_quartier}"
                        )
                    ->label('Quartier')
                    ->searchable()
                    ->preload()
                    ->required(),
                FileUpload::make('image')
                    ->label('Image')
                    ->disk('public')
                    ->directory('Livreur')
                    ->visibility('public')
                    ->image()
                    ->imagePreviewHeight('150')
                    ->enableDownload()
                    ->enableOpen()
                    ->openable()
                    ->previewable()
                    ->maxSize(10240),
                TextInput::make('compte')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('reference')
                    ->placeholder('-'),
                TextEntry::make('nom'),
                TextEntry::make('type')
                    ->placeholder('-'),
                TextEntry::make('adresse')
                    ->placeholder('-'),
                TextEntry::make('telephone')
                    ->placeholder('-'),
                TextEntry::make('email')
                    ->label('Email address')
                    ->placeholder('-'),
                TextEntry::make('contact')
                    ->placeholder('-'),
                TextEntry::make('categorie')
                    ->placeholder('-'),
                TextEntry::make('quartier.nom_quartier')
                    ->placeholder('-'),
                ImageEntry::make('image')
                    ->disk('public')
                    ->imageWidth(200)
                    ->imageHeight(200)
                    ->square()
                    ->url(fn ($state) => asset('storage/' . $state))
                    ->openUrlInNewTab(),
                TextEntry::make('user.name')
                    ->label('Utilisateur'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('compte')
                    ->numeric()
                    ->placeholder('-'),
                IconEntry::make('etat')
                    ->boolean()
                    ->placeholder('-'),
                TextEntry::make('statut')
                    ->placeholder('-'),

                RepeatableEntry::make('statutLivreurs')
                    ->label("Statut Livreur")
                    ->columnSpanFull()
                     ->table([
                            TableColumn::make('STATUT'),
                            TableColumn::make('MONTANT'),
                            TableColumn::make('MOTIF'),
                            TableColumn::make('UTILISATEUR'),
                            TableColumn::make('DATE'),
                        ])
                    ->schema([
                        TextEntry::make('statut'),
                        TextEntry::make('montant'),
                        TextEntry::make('motif'),
                        TextEntry::make('user.name'),
                        TextEntry::make('created_at')->dateTime('d-m-Y H:i'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('nom')
            ->columns([
                TextColumn::make('reference')
                    ->searchable(),
                TextColumn::make('nom')
                    ->searchable(),
                TextColumn::make('type')
                    ->searchable(),
                TextColumn::make('categorie')
                    ->searchable(),
                TextColumn::make('adresse')
                    ->searchable(),
                TextColumn::make('telephone')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('contact')
                    ->searchable(),
                TextColumn::make('quartier.nom_quartier')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Utilisateur')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('compte')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('statut')
                      ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'ACTIF' => 'success',
                    'EN_ATTENTE' => 'primary',
                    'BLOQUE' => 'danger',
                    'REJETE' => 'warning',
                })
                    ->sortable(),
                IconColumn::make('etat')
                    ->boolean()
                    ->sortable(),
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

                BulkAction::make('MiseAJourStatutLivreur')
                        ->label('Mise à jour du statut')
                        ->icon('heroicon-m-arrow-down-tray')
                        ->color('info')
                        ->modalHeading('Mettre à jour le statut des livreurs sélectionnés')
                        ->requiresConfirmation()
                       ->modalWidth(Width::ExtraLarge)
                        ->form([
                            Section::make('Détails de la mise à jour')->schema([
                                Select::make('statut')
                                    ->label('Nouveau Statut')
                                    ->options([
                                        'EN_ATTENTE' => 'EN_ATTENTE',
                                        'ACTIF'      => 'ACTIF',
                                        'BLOQUE'     => 'BLOQUE',
                                        'REJETE'     => 'REJETE',
                                    ])
                                    ->required()
                                    ->live(), 

                                TextInput::make('montant')
                                    ->label('Montant (Pénalité / Caution)')
                                    ->numeric()
                                    ->prefix('CFA')
                                    ->nullable(),

                                Textarea::make('motif')
                                    ->label('Motif (Obligatoire si Bloqué ou Rejeté)')
                                    ->required(fn ($get) => in_array($get('statut'), ['BLOQUE', 'REJETE']))
                                    ->columnSpanFull(),
                            ])->columns(2),
                        ])
                        ->action(function (Collection $records, array $data) {
                            try {
                                // Appel de la logique métier située dans le Model Livreur
                                foreach ($records as $record) {
                                    $record->mise_a_jour_statut($data);
                                }

                                Notification::make()
                                    ->title('Statuts mis à jour avec succès')
                                    ->body(count($records) . ' livreur(s) modifié(s).')
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

    ])
]);
                
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageLivreurs::route('/'),
        ];
    }
}
