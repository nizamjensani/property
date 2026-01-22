<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            Action::make('sendPasswordReset')
            ->label('Send Password Reset')
            ->icon('heroicon-m-key')
            ->requiresConfirmation()
            ->action(function ($record) {
                if (! $record?->is_active) {
                    Notification::make()
                        ->title('User is not active')
                        ->body('Please activate this user first before sending a password reset email.')
                        ->warning()
                        ->send();

                    return;
                }

                // Laravel default: sends reset link email to the user
                $record->sendPasswordResetNotification(
                    app('auth.password.broker')->createToken($record)
                );

                Notification::make()
                    ->title('Password reset link sent')
                    ->body("A password reset email has been sent to {$record->email}.")
                    ->success()
                    ->send();
            }),
            DeleteAction::make()
                ->visible(fn () => auth()->user()->role === 'superadmin'),
        ];
    }
}
