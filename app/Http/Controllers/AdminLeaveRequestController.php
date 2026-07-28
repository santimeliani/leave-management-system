<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Services\LeaveRequestService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AdminLeaveRequestController extends Controller
{
    use AuthorizesRequests;

    protected LeaveRequestService $requestService;

    public function __construct(LeaveRequestService $requestService)
    {
        $this->requestService = $requestService;
    }

    public function index()
    {
        $leaveRequests = $this->requestService->getAll();

        return view('admin.leave-requests.index', compact('leaveRequests'));
    }

    public function approve(LeaveRequest $leaveRequest)
    {
        $this->authorize('approve', $leaveRequest);

        $this->requestService->approve($leaveRequest);

        return redirect()
            ->route('admin.leave-requests.index')
            ->with('success', 'Pengajuan cuti berhasil disetujui.');
    }

    public function showRejectForm(LeaveRequest $leaveRequest)
    {
        $this->authorize('reject', $leaveRequest);

        return view('admin.leave-requests.reject', compact('leaveRequest'));
    }

    public function reject(Request $request, LeaveRequest $leaveRequest)
    {
        $this->authorize('reject', $leaveRequest);

        $request->validate([
            'reject_reason' => 'required|string|max:1000',
        ]);

        $this->requestService->reject($leaveRequest, $request->reject_reason);

        return redirect()
            ->route('admin.leave-requests.index')
            ->with('success', 'Pengajuan cuti berhasil ditolak.');
    }
}
