<?php

namespace App\Filament\Resources\Properties\Pages;

use App\Filament\Resources\Properties\PropertyResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditProperty extends EditRecord
{
    protected static string $resource = PropertyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $coords = $this->data['coordinates'] ?? null;

        if (!blank($coords)) {
            $parts = array_map('trim', explode(',', (string) $coords));

            if (count($parts) === 2 && is_numeric($parts[0]) && is_numeric($parts[1])) {
                $data['latitude']  = (float) $parts[0];
                $data['longitude'] = (float) $parts[1];
            }
        } else {
            // Optional: if user clears coordinates, clear DB columns too
            $data['latitude'] = null;
            $data['longitude'] = null;
        }

        return $data;
    }
}
