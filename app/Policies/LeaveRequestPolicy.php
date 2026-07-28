<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LeaveRequest;

class LeaveRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->id === $leaveRequest->user_id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'karyawan';
    }

    public function update(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->id === $leaveRequest->user_id
            && $leaveRequest->status === 'pending';
    }

    public function delete(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->id === $leaveRequest->user_id
            && $leaveRequest->status === 'pending';
    }

    public function approve(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->role === 'admin'
            && $leaveRequest->status === 'pending';
    }

    public function reject(User $user, LeaveRequest $leaveRequest): bool
    {
        return $user->role === 'admin'
            && $leaveRequest->status === 'pending';
    }
}
