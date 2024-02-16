<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user)
    {
        // Allow all authenticated users to view a list of users
        return $user->hasRole("admin");
    }

    public function view(User $user, User $targetUser)
    {
        // Allow the authenticated user to view a specific user
        return $user->hasRole("admin") || $user->id === $targetUser->id;
    }

    public function create(User $user)
    {
        // Allow the authenticated user to create a user
        return $user->hasRole("admin");
    }

    public function update(User $user, User $targetUser)
    {
        // Allow the authenticated user to update their own profile
        return $user->hasRole("admin");
    }
}
