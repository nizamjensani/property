<?php

namespace App\Filament\Resources\Properties\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use App\Models\State;
use App\Models\City;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class PropertyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Agent')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->visible(fn() => auth()->user()?->role === 'superadmin'),
                Hidden::make('user_id')
                    ->default(fn() => auth()->id())
                    ->dehydrated(true)
                    ->visible(fn() => auth()->user()?->role !== 'superadmin'),
                TextInput::make('reference_no')
                    ->live(onBlur: true) // validate when they leave the field (recommended)
                    ->unique(table: 'properties', column: 'reference_no', ignoreRecord: true)
                    ->required(),
                TextInput::make('title')
                    ->columnSpanFull()
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                Select::make('listing_type')
                    ->options(['sale' => 'Sale', 'rent' => 'Rent'])
                    ->required(),
                Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'archived' => 'Archived',
                        'sold' => 'Sold',
                        'rented' => 'Rented',
                    ])
                    ->default('draft')
                    ->required(),
                Select::make('property_type_id')
                    ->label('Property Type')
                    ->relationship('propertyType', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('tenure')
                    ->options(['freehold' => 'Freehold', 'leasehold' => 'Leasehold', 'unknown' => 'Unknown'])
                    ->default('unknown')
                    ->required(),
                Select::make('completion_status')
                    ->options(['under_construction' => 'Under construction', 'completed' => 'Completed'])
                    ->default('completed')
                    ->required(),
                TextInput::make('build_year'),
                TextInput::make('address'),
                TextInput::make('address_2'),
                TextInput::make('postcode'),

                Select::make('state')
                    ->label('State')
                    ->options(State::query()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->live() // make state reactive
                    ->afterStateUpdated(function (Set $set) {
                        $set('city', null); // reset city when state changes
                    })
                    ->required(),

                Select::make('city')
                    ->label('City / Mukim')
                    ->options(function (Get $get) {
                        $stateId = $get('state');

                        if (! $stateId) {
                            return [];
                        }

                        return City::query()
                            ->where('state_id', $stateId)
                            ->orderBy('name')
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->required()
                    ->disabled(fn(Get $get) => blank($get('state')))
                    ->hint(fn(Get $get) => blank($get('state')) ? 'Select a state first' : null),

                TextInput::make('latitude')
                    ->numeric(),
                TextInput::make('longitude')
                    ->numeric(),
                TextInput::make('price')
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('monthly_rent')
                    ->numeric(),
                TextInput::make('currency')
                    ->required()
                    ->default('MYR'),
                TextInput::make('deposit_months')
                    ->numeric(),
                TextInput::make('maintenance_fee')
                    ->numeric(),

                TextInput::make('built_up_sqft')
                    ->numeric(),
                TextInput::make('land_area_sqft')
                    ->numeric(),
                TextInput::make('bedrooms')
                    ->numeric(),
                TextInput::make('bathrooms')
                    ->numeric(),
                TextInput::make('carparks')
                    ->numeric(),
                TextInput::make('floor_level')
                    ->numeric(),
                Select::make('furnishing')
                    ->options(['unfurnished' => 'Unfurnished', 'partial' => 'Partial', 'fully' => 'Fully']),
                DatePicker::make('available_from'),
                DateTimePicker::make('published_at'),
                DateTimePicker::make('expires_at'),
                Toggle::make('negotiable')
                    ->required(),
                Textarea::make('notes_internal')
                    ->columnSpanFull(),
                Section::make('Media')
                    ->components([
                        SpatieMediaLibraryFileUpload::make('cover')
                            ->collection('cover')
                            ->image()
                            ->imageEditor()
                            ->maxFiles(1)
                            ->required(),

                        SpatieMediaLibraryFileUpload::make('images')
                            ->collection('images')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->imageEditor()
                            ->maxFiles(20),

                        SpatieMediaLibraryFileUpload::make('documents')
                            ->collection('documents')
                            ->multiple()
                            ->maxFiles(10),
                    ])
                    ->columns(1),

            ]);
    }
}
