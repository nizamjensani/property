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

        // Base queries
        $rentedQuery = Property::query()
            ->where('listing_type', 'rent');
        $saleQuery = Property::query()
            ->where('listing_type', 'sale');

        if ($user && $user->role !== 'superadmin') {
            $rentedQuery->where('user_id', $user->id);
            $saleQuery->where('user_id', $user->id);
        }
        // Get totals
        $rentedTotal = $rentedQuery->count();
        $saleTotal = $saleQuery->count();


        return [
            Stat::make('Total Rented Units', $rentedTotal),
            Stat::make('Total Sale Units', $saleTotal),

        ];
    }

    protected function getPollingInterval(): ?string
    {
        return '60s'; // auto refresh every 60s
    }
}
