<?php

namespace App\Filament\Resources\Properties\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\SpatieMediaLibraryFileEntry;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\RepeatableEntry;

class PropertyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('user.name'),
                    
                TextEntry::make('reference_no')
                    ->placeholder('-'),
                TextEntry::make('title'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('listing_type')
                    ->badge(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('property_type_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('tenure')
                    ->badge(),
                TextEntry::make('completion_status')
                    ->badge(),
                TextEntry::make('build_year')
                    ->placeholder('-'),
                TextEntry::make('address'),
                TextEntry::make('address_2')
                    ->placeholder('-'),
                TextEntry::make('postcode'),
                TextEntry::make('city')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('state')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('latitude')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('longitude')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('price')
                    ->money()
                    ->placeholder('-'),
                TextEntry::make('monthly_rent')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('currency'),
                TextEntry::make('deposit_months')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('maintenance_fee')
                    ->numeric()
                    ->placeholder('-'),
                IconEntry::make('negotiable')
                    ->boolean(),
                TextEntry::make('built_up_sqft')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('land_area_sqft')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('bedrooms')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('bathrooms')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('carparks')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('floor_level')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('furnishing')
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('available_from')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('published_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('expires_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('views_count')
                    ->numeric(),
                TextEntry::make('notes_internal')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                Section::make('Media')
                ->columnSpanFull()
                    ->schema([
                        // Cover (single)
                        SpatieMediaLibraryImageEntry::make('cover')
                            ->collection('cover')
                            ->label('Cover')
                            ->visible(fn($record) => $record?->hasMedia('cover')),

                        // Gallery (multiple)
                        SpatieMediaLibraryImageEntry::make('images')
                            ->collection('images')
                            ->label('Gallery')
                            ->visible(fn($record) => $record?->hasMedia('images')),

                        // Documents (PDFs etc.)
                        // SpatieMediaLibraryFileEntry::make('documents')
                        //     ->collection('documents')
                        //     ->label('Documents')
                        //     ->visible(fn($record) => $record?->hasMedia('documents')),
                    ])
                    ->columns(1),
                Section::make('Document')
                    ->schema([
                        // 2) SENARAI RECEIPTS DENGAN BUTANG DOWNLOAD
                        RepeatableEntry::make('documents')
                            ->label('Documents')
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Fail'),

                                TextEntry::make('download_url')
                                    ->label('Muat Turun')
                                    ->icon('heroicon-o-arrow-down-tray')   // icon next to text
                                    ->formatStateUsing(fn() => 'Muat Turun') // always show this text
                                    ->url(fn(?string $state): ?string => $state) // $state = URL
                                    ->openUrlInNewTab(),
                            ])
                            ->hiddenLabel()
                            ->columnSpanFull()
                            ->state(function ($record) {
                                if (! $record) {
                                    return [];
                                }
                
                                return $record->getMedia('documents')
                                    ->map(fn ($media) => [
                                        'name'         => $media->file_name,
                                        'download_url' => $media->getUrl(), // used by TextEntry
                                    ])
                                    ->values()
                                    ->all();
                            }),
                        ]),

                
            ]);
    }
}
