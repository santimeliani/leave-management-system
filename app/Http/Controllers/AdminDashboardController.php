<?php

namespace App\Http\Controllers;

use App\Services\LeaveRequestService;
use App\Models\LeaveRequest;

class AdminDashboardController extends Controller
{
    protected LeaveRequestService $requestService;

    public function __construct(LeaveRequestService $requestService)
    {
        $this->requestService = $requestService;
    }

    public function __invoke()
    {
        $stats = $this->requestService->getAdminStats();

        $pendingRequests = LeaveRequest::with(['user', 'leaveType'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'pendingRequests'));
    }
}
