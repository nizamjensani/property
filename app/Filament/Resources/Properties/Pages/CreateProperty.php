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

        return $data;
    }

}
