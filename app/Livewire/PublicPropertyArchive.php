<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\RateLimiter;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\State;
use App\Models\City;

class PublicPropertyArchive extends Component
{
    use WithPagination;

    // Draft (changes live in UI)

    public ?string $search = null;
    public ?string $listing_type = null;
    public ?array $property_type_ids = [];

    public ?int $state_id = null;
    public ?array $city_ids = [];

    public ?int $min_price = null;
    public ?int $max_price = null;

    public ?int $bedrooms = null;
    public ?int $bathrooms = null;

    public ?string $furnishing = null;
    public string $sort = 'latest';

    // Applied (used by query so results wait for Apply)
    public array $applied = [];

    public function mount(): void
    {
        $a = $this->applied ?: $this->currentDraft();
    }

    private function currentDraft(): array
    {
        return [
            'search' => $this->search,
            'listing_type' => $this->listing_type,
            'property_type_ids' => $this->property_type_ids,
            'state_id' => $this->state_id,
            'city_ids' => $this->city_ids,
            'min_price' => $this->min_price,
            'max_price' => $this->max_price,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'furnishing' => $this->furnishing,
            'sort' => $this->sort,
        ];
    }

    // This makes city update immediately when state changes
    public function updatedStateId(): void
    {
        $this->city_ids = [];
    }

    // Cities are computed live from draft state_id


    public function getAvailableCitiesProperty()
    {
        return blank($this->state_id)
            ? collect()
            : City::where('state_id', $this->state_id)
                ->orderBy('name')
                ->get(['id','name']);
    }



    public function applyFilters(): void
    {
        $key = 'public-property-apply:' . request()->ip();
        if (RateLimiter::tooManyAttempts($key, 30)) {
            $this->addError('rate', 'Too many requests. Please try again later.');
            return;
        }
        RateLimiter::hit($key, 60);

        // Normalize a bit
        $this->min_price = $this->min_price !== null ? max(0, (int) $this->min_price) : null;
        $this->max_price = $this->max_price !== null ? max(0, (int) $this->max_price) : null;

        $this->applied = $this->currentDraft();
        $this->resetPage();
        $this->resetErrorBag();
    }

    public function resetFilters(): void
    {
        $this->search = null;
        $this->listing_type = null;
        $this->property_type_ids = [];
        $this->state_id = null;
        $this->city_ids = [];
        $this->min_price = null;
        $this->max_price = null;
        $this->bedrooms = null;
        $this->bathrooms = null;
        $this->furnishing = null;
        $this->sort = 'latest';

        $this->applied = $this->currentDraft();
        $this->resetPage();
        $this->resetErrorBag();
    }

    public function getListingsProperty()
    {
        $a = $this->applied ?: $this->currentDraft();

        $q = Property::query()
            ->where('status', 'Published')
            ->with(['propertyType', 'user', 'media', 'cityRel', 'stateRel']);

            if (!blank($a['search'])) {
                $q->where('title', 'like', '%' . trim($a['search']) . '%');
            }
            

        if (!blank($a['listing_type'])) {
            $q->where('listing_type', $a['listing_type']);
        }

        if (!empty($a['property_type_ids'])) {
            $q->whereIn('property_type_id', $a['property_type_ids']);
        }

        if (!blank($a['state_id'])) {
            $q->where('state', (int) $a['state_id']);
        }

        if (!empty($a['city_ids'])) {
            $q->whereIn('city', array_map('intval', $a['city_ids']));
        }

        if (!blank($a['min_price'])) {
            // choose correct column based on listing_type if you want; this is simple:
            $q->where(function ($qq) use ($a) {
                $qq->where('price', '>=', (int) $a['min_price'])
                   ->orWhere('monthly_rent', '>=', (int) $a['min_price']);
            });
        }

        if (!blank($a['max_price'])) {
            $q->where(function ($qq) use ($a) {
                $qq->where('price', '<=', (int) $a['max_price'])
                   ->orWhere('monthly_rent', '<=', (int) $a['max_price']);
            });
        }

        if (!blank($a['bedrooms'])) {
            $q->where('bedrooms', '>=', (int) $a['bedrooms']);
        }

        if (!blank($a['bathrooms'])) {
            $q->where('bathrooms', '>=', (int) $a['bathrooms']);
        }

        if (!blank($a['furnishing'])) {
            $q->where('furnishing', $a['furnishing']);
        }

        // Whitelisted sorting
        if (($a['sort'] ?? 'latest') === 'price_asc') {
            $q->orderByRaw('COALESCE(price, monthly_rent, 0) asc');
        } elseif (($a['sort'] ?? 'latest') === 'price_desc') {
            $q->orderByRaw('COALESCE(price, monthly_rent, 0) desc');
        } else {
            $q->latest();
        }

        return $q->paginate(12);
    }

    public function render()
    {
        return view('livewire.public-property-archive', [
            'properties' => $this->listings,
            'propertyTypes' => PropertyType::where('is_active', true)->orderBy('sort_order')->get(['id','name']),
            'states' => State::orderBy('name')->get(['id','name']),
        ]);
    }
}
