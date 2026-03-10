<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\IconEntry;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('email')
                    ->label('Email address'),
                TextEntry::make('email_verified_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('phone_number')
                    ->placeholder('-'),
                TextEntry::make('username')
                    ->placeholder('-'),
                TextEntry::make('role'),
                IconEntry::make('is_active')
                ->boolean(),
                TextEntry::make('active_listing')
                    ->label('Active Listing')
                    ->state(fn ($record) => $record->properties()
                        ->where('status', 'published')
                        ->count()),

                TextEntry::make('sold_rented_listing')
                    ->label('Sold/Rented Listing')
                    ->state(fn ($record) => $record->properties()
                        ->whereIn('status', ['sold', 'rented'])
                        ->count()),
                    
                // TextEntry::make('first_address')
                //     ->placeholder('-'),
                // TextEntry::make('second_address')
                //     ->placeholder('-'),
                // TextEntry::make('postcode')
                //     ->placeholder('-'),
                // TextEntry::make('city')
                //     ->placeholder('-'),
                // TextEntry::make('state')
                //     ->placeholder('-'),
            ]);
    }
}
