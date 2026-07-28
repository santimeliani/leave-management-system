<?php

namespace App\Listeners;

use App\Events\LeaveRequestApproved;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class UpdateLeaveBalanceOnApproval implements ShouldQueue
{
    public function handle(LeaveRequestApproved $event): void
    {
        $leaveRequest = $event->leaveRequest;

        Log::info("Leave request #{$leaveRequest->id} approved for user #{$leaveRequest->user_id}", [
            'leave_type' => $leaveRequest->leaveType->name,
            'start_date' => $leaveRequest->start_date->format('Y-m-d'),
            'end_date' => $leaveRequest->end_date->format('Y-m-d'),
            'days' => $leaveRequest->duration_in_days,
        ]);
    }
}
