<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;

class PropertyPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        // superadmin can do anything
        if ($user->role === 'superadmin') {
            return true;
        }

        return null;
    }

    public function view(User $user, Property $property): bool
    {
        return true;
    }

    public function update(User $user, Property $property): bool
    {
        return $property->user_id === $user->id;
    }

    public function delete(User $user, Property $property): bool
    {
        return $property->user_id === $user->id;
    }

    public function viewAny(User $user): bool
    {
        // allow listing; filtering is handled elsewhere
        return true;
    }
}
