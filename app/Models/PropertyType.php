<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyType extends Model
{
    protected $table = 'property_types';
    protected $fillable = [
        'name',
        'slug',
        'category',
        'is_active',
        'sort_order',
    ];
}
