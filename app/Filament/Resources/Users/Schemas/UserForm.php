<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Toggle;

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
                    ->live(onBlur: true) // validate when they leave the field (recommended)
                    ->unique(table: 'users', column: 'email', ignoreRecord: true)
                    ->required(),
                Select::make('role')
                ->options(fn () => array_filter([
                    'admin' => 'Agent',
                    'superadmin' => (Auth::user()?->role === 'superadmin') ? 'Super Admin' : null,
                ]))           
                    ->searchable()         
                    ->preload()
                    ->required()
                    ->dehydrated(true),
                // DateTimePicker::make('email_verified_at'),
                TextInput::make('username')
                ->required()
                ->live(onBlur: true) // validate when they leave the field (recommended)
                ->unique(table: 'users', column: 'username', ignoreRecord: true)
                ->validationMessages([
                    'unique' => 'Username already exists. Please choose another.',
                ]),
                TextInput::make('phone_number')
                    ->tel(),
                Toggle::make('is_active')
                    ->default(true)
                    ->required(),
                // TextInput::make('first_address'),
                // TextInput::make('second_address'),
                // TextInput::make('postcode'),
                // TextInput::make('city'),
                // TextInput::make('state'),
            ]);
    }
}
