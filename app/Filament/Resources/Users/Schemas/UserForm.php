<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(),
                TextInput::make('phone_number')
                    ->tel(),
                TextInput::make('username'),
                TextInput::make('role')
                    ->required()
                    ->default('user'),
                TextInput::make('first_address'),
                TextInput::make('second_address'),
                TextInput::make('postcode'),
                TextInput::make('city'),
                TextInput::make('state'),
            ]);
    }
}
