<?php

namespace App\Listeners;

use App\Events\LeaveRequestRejected;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendRejectionNotification implements ShouldQueue
{
    public function handle(LeaveRequestRejected $event): void
    {
        $leaveRequest = $event->leaveRequest;

        Log::info("Leave request #{$leaveRequest->id} rejected for user #{$leaveRequest->user_id}", [
            'leave_type' => $leaveRequest->leaveType->name,
            'reject_reason' => $leaveRequest->reject_reason,
        ]);
    }
}
