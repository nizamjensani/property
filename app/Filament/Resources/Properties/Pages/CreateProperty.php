<?php

namespace App\Filament\Resources\Properties\Pages;

use App\Filament\Resources\Properties\PropertyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProperty extends CreateRecord
{
    protected static string $resource = PropertyResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (auth()->user()?->role !== 'superadmin') {
            $data['user_id'] = auth()->id();
        }
        $coords = $this->data['coordinates'] ?? null;

        if (!blank($coords)) {
            $parts = array_map('trim', explode(',', (string) $coords));

            if (count($parts) === 2 && is_numeric($parts[0]) && is_numeric($parts[1])) {
                $data['latitude']  = (float) $parts[0];
                $data['longitude'] = (float) $parts[1];
            }
        }
        return $data;
    }

}
