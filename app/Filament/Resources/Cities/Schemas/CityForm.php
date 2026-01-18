<?php

namespace App\Filament\Resources\Cities\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class CityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                ->required(),
                TextInput::make('slug')
                    ->required(),
                Select::make('state_id')
                    ->relationship('state', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
               
            ]);
    }
}
