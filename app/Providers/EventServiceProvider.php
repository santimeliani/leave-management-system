<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\LeaveRequestApproved;
use App\Events\LeaveRequestRejected;
use App\Listeners\UpdateLeaveBalanceOnApproval;
use App\Listeners\SendRejectionNotification;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        LeaveRequestApproved::class => [
            UpdateLeaveBalanceOnApproval::class,
        ],
        LeaveRequestRejected::class => [
            SendRejectionNotification::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
