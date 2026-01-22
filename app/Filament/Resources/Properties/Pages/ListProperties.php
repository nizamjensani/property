<?php

namespace App\Filament\Resources\Properties\Pages;

use App\Filament\Resources\Properties\PropertyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListProperties extends ListRecords
{
    protected static string $resource = PropertyResource::class;
    protected static ?string $title = 'All Properties';
    protected static ?string $navigationLabel = 'All Properties';
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
