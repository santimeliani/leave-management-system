<?php

namespace App\Http\Controllers;

use App\Services\LeaveRequestService;
use App\Services\LeaveBalanceService;

class KaryawanDashboardController extends Controller
{
    protected LeaveRequestService $requestService;
    protected LeaveBalanceService $balanceService;

    public function __construct(LeaveRequestService $requestService, LeaveBalanceService $balanceService)
    {
        $this->requestService = $requestService;
        $this->balanceService = $balanceService;
    }

    public function __invoke()
    {
        $userId = auth()->id();
        $stats = $this->requestService->getStatsForUser($userId);
        $leaveBalances = $this->balanceService->getBalancesForUser($userId);

        $recentRequests = $this->requestService->getForUser($userId)->take(5);

        return view('karyawan.dashboard', compact('stats', 'leaveBalances', 'recentRequests'));
    }
}
