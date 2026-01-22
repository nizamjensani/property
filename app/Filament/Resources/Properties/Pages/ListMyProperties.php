<?php

namespace App\Filament\Resources\Properties\Pages;

use App\Filament\Resources\Properties\PropertyResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\Icons\Heroicon;
use BackedEnum;

class ListMyProperties extends ListRecords
{
    protected static string $resource = PropertyResource::class;

    protected static ?string $navigationLabel = 'My Property';
    protected static ?string $title = 'My Property';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Home;
    protected static ?int $navigationSort = 5;

    public static function shouldRegisterNavigation(array $parameters = []): bool
    {
        return true;
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->where('user_id', auth()->id());
    }
}
