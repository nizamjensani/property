<?php

namespace App\Filament\Resources\Properties;

use App\Filament\Resources\Properties\Pages\CreateProperty;
use App\Filament\Resources\Properties\Pages\EditProperty;
use App\Filament\Resources\Properties\Pages\ListProperties;
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

class PropertyResource extends Resource
{
    protected static ?string $model = Property::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Home;

    protected static ?string $recordTitleAttribute = 'title';

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
            'create' => CreateProperty::route('/create'),
            'view' => ViewProperty::route('/{record}'),
            'edit' => EditProperty::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = Auth::user();

        // If not logged in, return empty (or keep as-is depending on your needs)
        if (! $user) {
            return $query->whereRaw('1=0');
        }

        // Superadmin can see everyone
        if ($user->role === 'superadmin') {
            return $query;
        }

        // Others can only see themselves
        return $query->where('id', $user->id);
    }
}
