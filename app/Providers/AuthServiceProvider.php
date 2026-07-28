<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Policies\LeaveRequestPolicy;
use App\Policies\LeaveTypePolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        LeaveRequest::class => LeaveRequestPolicy::class,
        LeaveType::class => LeaveTypePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
