<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Property extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        // Ownership / agent
        'user_id',
        'reference_no',
    
        // Core
        'title',
        'description',
        'listing_type',
        'status',
        'property_type_id',
        'tenure',
        'completion_status',
        'build_year',
    
        // Address
        'address',
        'address_2',
        'postcode',
        'city',
        'state',
    
        // Geo
        'latitude',
        'longitude',
    
        // Pricing
        'price',
        'monthly_rent',
        'currency',
        'deposit_months',
        'maintenance_fee',
        'negotiable',
    
        // Specs
        'built_up_sqft',
        'land_area_sqft',
        'bedrooms',
        'bathrooms',
        'carparks',
        'floor_level',
        'furnishing',
    
        // Publish / availability
        'available_from',
        'published_at',
        'expires_at',
    
        // Internal
        'notes_internal',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function propertyType()
    {
        return $this->belongsTo(PropertyType::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')->singleFile();   // 1 cover image
        $this->addMediaCollection('images');                // gallery
        $this->addMediaCollection('documents');             // PDFs etc
    }
    
}
