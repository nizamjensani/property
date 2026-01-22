<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        if (blank($this->record->password) && $this->record->is_active) {
                Password::sendResetLink(['email' => $this->record->email]);
            }    
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
    
}
