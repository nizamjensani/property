<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Schemas\Schema;
use App\Models\City;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\State;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;

class PropertyArchive extends Page implements HasForms
{
    use InteractsWithForms;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::ListBullet;
    protected static ?string $title = 'Filter Listings';

    protected string $view = 'filament.pages.property-archive';

    public array $filters = [
        'q' => null,
        'listing_type' => null,
        'property_type_ids' => [],
        'state' => null,
        'cities' => [],
        'min_price' => null,
        'max_price' => null,
        'bedrooms' => null,
        'bathrooms' => null,
        'furnishing' => null,
        'sort' => 'latest',
    ];

    public array $appliedFilters = [];

    protected function getForms(): array
    {
        return ['form'];
    }


    public function mount(): void
    {
        $this->appliedFilters = $this->filters;     // initial applied = default
        $this->getForm('form')->fill($this->filters);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('filters')

            ->components([
                TextInput::make('q')
                    ->label('Search')
                    ->placeholder('Title'),

                Select::make('listing_type')
                    ->label('Listing Type')
                    ->options([
                        'sale' => 'Sale',
                        'rent' => 'Rent',
                    ])
                    ->native(false),

                Select::make('property_type_ids')
                    ->label('Property Type')
                    ->options(
                        fn() => PropertyType::query()
                            ->where('is_active', true)
                            ->orderBy('sort_order')
                            ->pluck('name', 'id')
                            ->all()
                    )
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->multiple(),
                    

                    Select::make('state')
                    ->label('State')
                    ->options(fn() => State::query()->orderBy('name')->pluck('name', 'id')->all())
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(function (Set $set) {
                        $set('cities', null);
                    }),
                

                Select::make('cities')
                    ->label('City / Mukim')
                    ->options(function (Get $get) {
                        $stateId = $get('state');
                        if (! $stateId) return [];

                        return City::query()
                            ->where('state_id', $stateId)
                            ->orderBy('name')
                            ->pluck('name', 'id')
                            ->all();
                    })
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->multiple()
                    ->disabled(fn(Get $get) => blank($get('state'))),

                TextInput::make('min_price')
                    ->label('Min Price')
                    ->numeric(),

                TextInput::make('max_price')
                    ->label('Max Price')
                    ->numeric(),

                Select::make('bedrooms')
                    ->label('Bedrooms (min)')
                    ->options([1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => '5+'])
                    ->native(false),

                Select::make('bathrooms')
                    ->label('Bathrooms (min)')
                    ->options([1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => '5+'])
                    ->native(false),

                Select::make('furnishing')
                    ->label('Furnishing')
                    ->options([
                        'unfurnished' => 'Unfurnished',
                        'partial' => 'Partial',
                        'fully' => 'Fully',
                    ])
                    ->native(false),

                Select::make('sort')
                    ->label('Sort')
                    ->options([
                        'latest' => 'Latest',
                        'price_asc' => 'Price: Low to High',
                        'price_desc' => 'Price: High to Low',
                    ])
                    ->native(false),

            ])
            ->columns(4);
    }

    public function resetFilters(): void
    {
        $this->filters = [
            'q' => null,
            'listing_type' => null,
            'property_type_ids' => [],
            'state' => null,
            'cities' => [],
            'min_price' => null,
            'max_price' => null,
            'bedrooms' => null,
            'bathrooms' => null,
            'furnishing' => null,
            'sort' => 'latest',
        ];
    
        $this->appliedFilters = $this->filters;
    
        $this->getForm('form')->fill($this->filters);
    
        $this->resetPage();
    }    
    public function resetPage(): void
    {
        if (method_exists($this, 'setPage')) {
            $this->setPage(1);
        }
    }


public function getListingsProperty(): LengthAwarePaginator
{
    return $this->getPropertiesQuery()->paginate(12);
}

public function applyFilters(): void
{
    // copy draft -> applied
    $this->appliedFilters = $this->filters;

    // reset pagination so results start at page 1
    $this->resetPage();
}

protected function getPropertiesQuery()
{
    $f = $this->appliedFilters ?: $this->filters;

    $query = Property::query()
        ->with(['user:id,name,phone_number', 'propertyType:id,name'])
        ->where('status','Published');

    // your filters (same as before)...
    if (! empty($f['q'])) {
        $q = trim($f['q']);
        $query->where(fn ($sub) =>
            $sub->where('title', 'like', "%{$q}%")
        );
    }

    if (! empty($f['listing_type'])) $query->where('listing_type', $f['listing_type']);
    if (! empty($f['property_type_ids']) && is_array($f['property_type_ids'])) {
        $query->whereIn('property_type_id', $f['property_type_ids']);
    }
    if (! empty($f['state'])) $query->where('state', $f['state']);
    // if (! empty($f['city'])) $query->where('city', $f['city']);
    if (! empty($f['cities']) && is_array($f['cities'])) {
        $query->whereIn('city', $f['cities']);
    }
    if (! empty($f['bedrooms'])) $query->where('bedrooms', '>=', (int) $f['bedrooms']);
    if (! empty($f['bathrooms'])) $query->where('bathrooms', '>=', (int) $f['bathrooms']);
    if (! empty($f['furnishing'])) $query->where('furnishing', $f['furnishing']);

    $priceField = ($f['listing_type'] ?? null) === 'rent' ? 'monthly_rent' : 'price';
    if ($f['min_price'] !== null && $f['min_price'] !== '') $query->where($priceField, '>=', (int) $f['min_price']);
    if ($f['max_price'] !== null && $f['max_price'] !== '') $query->where($priceField, '<=', (int) $f['max_price']);

    match ($f['sort'] ?? 'latest') {
        'price_asc'  => $query->orderBy($priceField, 'asc'),
        'price_desc' => $query->orderBy($priceField, 'desc'),
        default      => $query->orderByDesc('published_at')->orderByDesc('id'),
    };

    return $query;
}

}
