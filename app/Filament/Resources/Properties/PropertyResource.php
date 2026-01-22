<?php

namespace App\Filament\Resources\Properties;

use App\Filament\Resources\Properties\Pages\CreateProperty;
use App\Filament\Resources\Properties\Pages\EditProperty;
use App\Filament\Resources\Properties\Pages\ListProperties;
use App\Filament\Resources\Properties\Pages\ListMyProperties;
use App\Filament\Resources\Properties\Pages\ViewProperty;
use App\Filament\Resources\Properties\Schemas\PropertyForm;
use App\Filament\Resources\Properties\Schemas\PropertyInfolist;
use App\Filament\Resources\Properties\Tables\PropertiesTable;
use App\Models\Property;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Navigation\NavigationItem;

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Home;

    // protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $title = 'All Properties';
    protected static ?string $navigationLabel = 'All Properties';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return PropertyForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PropertyInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PropertiesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProperties::route('/'),
            'my'    => ListMyProperties::route('/my'),
            'create' => CreateProperty::route('/create'),
            'view' => ViewProperty::route('/{record}'),
            'edit' => EditProperty::route('/{record}/edit'),
        ];
    }



    public static function getNavigationItems(): array
    {
        return [
            ...parent::getNavigationItems(),

            NavigationItem::make('My Property')
                ->url(static::getUrl('my'))
                ->icon(Heroicon::Home)
                ->sort(1)
                ->visible(fn(): bool => auth()->check()),
        ];
    }
}
