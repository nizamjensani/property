<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\State;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index(Request $request)
{
    $properties = Property::query()
        ->when($request->q, fn($q) => $q->where('title', 'like', '%'.$request->q.'%'))
        ->when($request->listing_type, fn($q) => $q->where('listing_type', $request->listing_type))
        ->when($request->state_id, fn($q) => $q->where('state', $request->state_id))
        ->when($request->min_price, fn($q) => $q->where('price', '>=', $request->min_price))
        ->when($request->max_price, fn($q) => $q->where('price', '<=', $request->max_price))
        ->when($request->bedrooms_min, fn($q) => $q->where('bedrooms', '>=', $request->bedrooms_min))
        ->when($request->bathrooms_min, fn($q) => $q->where('bathrooms', '>=', $request->bathrooms_min))
        ->when($request->furnishing, fn($q) => $q->where('furnishing', $request->furnishing))
        ->when($request->property_type_ids, fn($q) => $q->whereIn('property_type_id', (array) $request->property_type_ids))
        ->when($request->city_ids, fn($q) => $q->whereIn('city', (array) $request->city_ids))
        ->when($request->sort, function ($q) use ($request) {
            return match ($request->sort) {
                'price_asc'  => $q->orderBy('price', 'asc'),
                'price_desc' => $q->orderBy('price', 'desc'),
                default      => $q->latest(),
            };
        }, fn($q) => $q->latest())
        ->paginate(12);

    return view('welcome', [
        'properties'     => $properties,
        'propertyTypes'  => PropertyType::where('is_active', true)->orderBy('sort_order')->pluck('name', 'id'),
        'states'         => State::orderBy('name')->pluck('name', 'id'),
        'cities'         => City::orderBy('name')->pluck('name', 'id'),
    ]);
}

    
}
