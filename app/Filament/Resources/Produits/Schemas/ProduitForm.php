<?php

namespace App\Filament\Resources\Produits\Schemas;

use App\Models\Produit;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class ProduitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nom')
                    ->label('Produit')
                    ->required(),
                FileUpload::make('image')
                    ->label('Image')
                    ->disk('public')
                    ->directory('Produits')
                    ->visibility('public')
                    ->image()
                    ->imagePreviewHeight('150')
                    ->enableDownload()
                    ->enableOpen()
                    ->openable()
                    ->previewable()
                    ->reorderable()
                    ->appendFiles(),

                Hidden::make('user_id')
                    ->default(fn () => Auth::id()),

                Textarea::make('description'),
                Select::make('type')
                    ->label('Type')
                    ->options([
                        'EN GROS' => 'EN GROS',
                        'EN DETAIL' => 'EN DETAIL',
                    ])
                    ->required(),

            ]);
    }
}
