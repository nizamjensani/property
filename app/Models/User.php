<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Auth\MultiFactor\Email\Contracts\HasEmailAuthentication;
use Filament\Auth\MultiFactor\Email\Concerns\InteractsWithEmailAuthentication;
use Filament\Models\Contracts\FilamentUser;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser, HasEmailAuthentication
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, InteractsWithEmailAuthentication;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'phone_number',
        // 'first_address',
        // 'second_address',
        // 'postcode',
        // 'city',
        // 'state',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // adjust to your logic
        return in_array($this->role, ['superadmin', 'admin'], true);
        // OR if using Spatie:
        // return $this->hasAnyRole(['superadmin', 'admin']);
    }
}
