<?php

namespace App\Filament\Widgets;

use App\Models\Property;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PropertyStatusTotals extends StatsOverviewWidget
{
    protected static ?int $sort = -10;

    protected function getStats(): array
    {
        $user = auth()->user();
        $own = Property::query()
        ->where('user_id', $user->id )
        ->count();
        $rentTotal = Property::query()
            ->where('listing_type', 'rent')
            ->where('status', 'Published')
            ->count();
    
        $saleTotal = Property::query()
            ->where('listing_type', 'sale')
            ->where('status', 'Published')
            ->count();
    
        $soldTotal = Property::query()
            ->where('listing_type', 'sale')
            ->where('status', 'sold')   // change to 'Sold' if your DB uses that
            ->count();
    
        $rentedOutTotal = Property::query()
            ->where('listing_type', 'rent')
            ->where('status', 'rented') // change to 'Rented' if your DB uses that
            ->count();
    
        return [
            Stat::make('Total Published For Sale', $saleTotal),
            Stat::make('Total Published For Rent', $rentTotal),
            Stat::make('Total Sold Out', $soldTotal),
            Stat::make('Total Rented Out', $rentedOutTotal),
            Stat::make('Total Own Listing', $own),
        ];
    }
    

    protected function getPollingInterval(): ?string
    {
        return '60s'; // auto refresh every 60s
    }
}
