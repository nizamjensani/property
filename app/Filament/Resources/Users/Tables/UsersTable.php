<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\IconColumn;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('phone_number')
                    ->searchable(),
                TextColumn::make('username')
                    ->searchable(),
                TextColumn::make('role')
                    ->searchable(),
                // TextColumn::make('first_address')
                //     ->searchable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('second_address')
                //     ->searchable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('postcode')
                //     ->searchable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('city')
                //     ->searchable()
                //     ->toggleable(isToggledHiddenByDefault: true),
                // TextColumn::make('state')
                //     ->searchable()
                //     ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
