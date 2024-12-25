<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserLeaveRequest;

class UserLeaveRequestPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user)
    {
        return $user->hasPermission("approve-request");
    }

    public function view(User $user, UserLeaveRequest $userLeaveRequest)
    {
        return $user->hasPermission("approve-request") || $user->id === $userLeaveRequest->user_id;
    }

    public function update(User $user, UserLeaveRequest $userLeaveRequest)
    {
        return $user->hasPermission("approve-request") || $user->id === $userLeaveRequest->user_id;
    }
}
