<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LeaveType;

class LeaveTypePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(User $user, LeaveType $leaveType): bool
    {
        return $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, LeaveType $leaveType): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, LeaveType $leaveType): bool
    {
        return $user->role === 'admin';
    }
}
