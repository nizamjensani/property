<?php

namespace App\Filament\Resources\PropertyTypes\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class PropertyTypeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required()
                    ->live(onBlur: true) // validate when they leave the field (recommended)
                    ->unique(table: 'property_types', column: 'slug', ignoreRecord: true)
                    ->validationMessages([
                        'unique' => 'slug already exists. Please choose another.',
                    ]),
                Select::make('category')->
                    options([
                        'residential' => 'Residential',
                        'commercial' => 'Commercial',
                        'land' => 'Land',
                        'industrial' => 'Industrial',
                    ])
                    ->searchable()
                    ->preload()
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
