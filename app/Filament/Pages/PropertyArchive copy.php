<?php

namespace App\Filament\Pages;

use App\Models\City;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\State;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
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

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Home;
    protected string $view = 'filament.pages.property-archive';

    // keep these as static (they are static on Filament v4)
    protected static ?string $navigationLabel = 'Property Archive';
    protected static ?string $title = 'Property Archive';

    // Filters state


    public ?array $filters = [
        'q' => null,
        'listing_type' => null,
        'property_type_id' => null,
        'state' => null,
        'city' => null,
        'min_price' => null,
        'max_price' => null,
        'bedrooms' => null,
        'bathrooms' => null,
        'furnishing' => null,
        'sort' => 'latest',
    ];
    /** Ensure the default form instance exists */
    public ?Form $form = null;

    public function mount(): void
    {
        // At mount time, Filament will have initialized the default form instance
        $this->form?->fill($this->filters);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('filters')
            ->schema([
                TextInput::make('q')
                    ->label('Search')
                    ->placeholder('Title / Address / Postcode')
                    ->live(onBlur: true),

                Select::make('listing_type')
                    ->label('Listing Type')
                    ->options([
                        'sale' => 'Sale',
                        'rent' => 'Rent',
                    ])
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(fn () => $this->resetPage()),

                Select::make('property_type_id')
                    ->label('Property Type')
                    ->options(fn () => PropertyType::query()
                        ->where('is_active', true)
                        ->orderBy('sort_order')
                        ->pluck('name', 'id')
                        ->all()
                    )
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(fn () => $this->resetPage()),

                Select::make('state')
                    ->label('State')
                    ->options(fn () => State::query()->orderBy('name')->pluck('name', 'id')->all())
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(function (Set $set) {
                        $set('city', null);
                        $this->resetPage();
                    }),

                Select::make('city')
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
                    ->disabled(fn (Get $get) => blank($get('state')))
                    ->live()
                    ->afterStateUpdated(fn () => $this->resetPage()),

                TextInput::make('min_price')
                    ->label('Min Price')
                    ->numeric()
                    ->live(onBlur: true),

                TextInput::make('max_price')
                    ->label('Max Price')
                    ->numeric()
                    ->live(onBlur: true),

                Select::make('bedrooms')
                    ->label('Bedrooms (min)')
                    ->options([1=>1,2=>2,3=>3,4=>4,5=>'5+'])
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(fn () => $this->resetPage()),

                Select::make('bathrooms')
                    ->label('Bathrooms (min)')
                    ->options([1=>1,2=>2,3=>3,4=>4,5=>'5+'])
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(fn () => $this->resetPage()),

                Select::make('furnishing')
                    ->label('Furnishing')
                    ->options([
                        'unfurnished' => 'Unfurnished',
                        'partial' => 'Partial',
                        'fully' => 'Fully',
                    ])
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(fn () => $this->resetPage()),

                Select::make('sort')
                    ->label('Sort')
                    ->options([
                        'latest' => 'Latest',
                        'price_asc' => 'Price: Low to High',
                        'price_desc' => 'Price: High to Low',
                    ])
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(fn () => $this->resetPage()),
            ])
            ->columns(4);
    }

    public function getProperties(): LengthAwarePaginator
    {
        $f = $this->filters;
        $user = auth()->user();

        $query = Property::query()
            ->with([
                'user:id,name,phone_number',
                'propertyType:id,name',
            ])
            ->where('status', 'published');

        // Optional: If non-superadmin should only see their own listings:
        // if ($user?->role !== 'superadmin') $query->where('user_id', $user->id);

        if (! empty($f['q'])) {
            $q = trim($f['q']);
            $query->where(function ($sub) use ($q) {
                $sub->where('title', 'like', "%{$q}%")
                    ->orWhere('address', 'like', "%{$q}%")
                    ->orWhere('postcode', 'like', "%{$q}%");
            });
        }

        if (! empty($f['listing_type'])) {
            $query->where('listing_type', $f['listing_type']);
        }

        if (! empty($f['property_type_id'])) {
            $query->where('property_type_id', $f['property_type_id']);
        }

        if (! empty($f['state'])) {
            $query->where('state', $f['state']);
        }

        if (! empty($f['city'])) {
            $query->where('city', $f['city']);
        }

        if (! empty($f['bedrooms'])) {
            $min = (int) $f['bedrooms'];
            $query->where('bedrooms', '>=', $min);
        }

        if (! empty($f['bathrooms'])) {
            $min = (int) $f['bathrooms'];
            $query->where('bathrooms', '>=', $min);
        }

        if (! empty($f['furnishing'])) {
            $query->where('furnishing', $f['furnishing']);
        }

        // Price filters: sale uses price, rent uses monthly_rent
        $priceField = ($f['listing_type'] ?? null) === 'rent' ? 'monthly_rent' : 'price';

        if ($f['min_price'] !== null && $f['min_price'] !== '') {
            $query->where($priceField, '>=', (int) $f['min_price']);
        }
        if ($f['max_price'] !== null && $f['max_price'] !== '') {
            $query->where($priceField, '<=', (int) $f['max_price']);
        }

        // Sort
        match ($f['sort'] ?? 'latest') {
            'price_asc'  => $query->orderBy($priceField, 'asc'),
            'price_desc' => $query->orderBy($priceField, 'desc'),
            default      => $query->orderByDesc('published_at')->orderByDesc('id'),
        };

        return $query->paginate(12);
    }

    public function resetFilters(): void
    {
        $this->filters = [
            'q' => null,
            'listing_type' => null,
            'property_type_id' => null,
            'state' => null,
            'city' => null,
            'min_price' => null,
            'max_price' => null,
            'bedrooms' => null,
            'bathrooms' => null,
            'furnishing' => null,
            'sort' => 'latest',
        ];

        $this->form->fill(['filters' => $this->filters]);
        $this->resetPage();
    }

    // Simple pagination helper for Livewire v3
    public function resetPage(): void
    {
        if (method_exists($this, 'setPage')) {
            $this->setPage(1);
        }
    }
}

