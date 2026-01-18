<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PropertyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            // Residential
            ['name' => 'Apartment',      'category' => 'Residential', 'sort_order' => 10],
            ['name' => 'Condominium',    'category' => 'Residential', 'sort_order' => 20],
            ['name' => 'Serviced Residence', 'category' => 'Residential', 'sort_order' => 30],
            ['name' => 'Townhouse',      'category' => 'Residential', 'sort_order' => 40],
            ['name' => 'Terrace / Link House', 'category' => 'Residential', 'sort_order' => 50],
            ['name' => 'Semi-Detached',  'category' => 'Residential', 'sort_order' => 60],
            ['name' => 'Bungalow',       'category' => 'Residential', 'sort_order' => 70],

            // Commercial
            ['name' => 'Shop Lot',       'category' => 'Commercial',  'sort_order' => 110],
            ['name' => 'Office',         'category' => 'Commercial',  'sort_order' => 120],
            ['name' => 'Retail',         'category' => 'Commercial',  'sort_order' => 130],
            ['name' => 'Factory',        'category' => 'Commercial',  'sort_order' => 140],
            ['name' => 'Warehouse',      'category' => 'Commercial',  'sort_order' => 150],

            // Land
            ['name' => 'Residential Land', 'category' => 'Land',      'sort_order' => 210],
            ['name' => 'Commercial Land',  'category' => 'Land',      'sort_order' => 220],
            ['name' => 'Agricultural Land','category' => 'Land',      'sort_order' => 230],
            ['name' => 'Industrial Land',  'category' => 'Land',      'sort_order' => 240],
        ];

        foreach ($types as $t) {
            DB::table('property_types')->updateOrInsert(
                ['slug' => Str::slug($t['name'])],
                [
                    'name'       => $t['name'],
                    'category'   => $t['category'],
                    'is_active'  => true,
                    'sort_order' => $t['sort_order'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
