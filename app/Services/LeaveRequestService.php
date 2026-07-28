<?php

namespace App\Services;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Events\LeaveRequestApproved;
use App\Events\LeaveRequestRejected;
use App\Exceptions\InsufficientLeaveBalanceException;
use Illuminate\Support\Facades\DB;

class LeaveRequestService
{
    protected LeaveBalanceService $balanceService;

    public function __construct(LeaveBalanceService $balanceService)
    {
        $this->balanceService = $balanceService;
    }

    public function getForUser(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return LeaveRequest::with('leaveType')
            ->where('user_id', $userId)
            ->latest()
            ->get();
    }

    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return LeaveRequest::with(['user', 'leaveType'])
            ->latest()
            ->get();
    }

    public function create(array $data, int $userId): LeaveRequest
    {
        $startDate = \Carbon\Carbon::parse($data['start_date']);
        $endDate = \Carbon\Carbon::parse($data['end_date']);
        $daysNeeded = $this->balanceService->calculateDays($startDate, $endDate);

        $this->balanceService->checkAndDeductBalance(
            $userId,
            $data['leave_type_id'],
            $daysNeeded
        );

        return LeaveRequest::create([
            'user_id'       => $userId,
            'leave_type_id' => $data['leave_type_id'],
            'start_date'    => $data['start_date'],
            'end_date'      => $data['end_date'],
            'reason'        => $data['reason'],
            'status'        => 'pending',
        ]);
    }

    public function approve(LeaveRequest $leaveRequest): LeaveRequest
    {
        $year = (int) $leaveRequest->start_date->format('Y');
        $daysUsed = $this->balanceService->calculateDays(
            $leaveRequest->start_date,
            $leaveRequest->end_date
        );

        DB::transaction(function () use ($leaveRequest, $year, $daysUsed) {
            $leaveRequest->update([
                'status' => 'approved',
                'decision_at' => now(),
            ]);

            $this->balanceService->deductBalance(
                $leaveRequest->user_id,
                $leaveRequest->leave_type_id,
                $daysUsed,
                $year
            );
        });

        event(new LeaveRequestApproved($leaveRequest));

        return $leaveRequest->fresh();
    }

    public function reject(LeaveRequest $leaveRequest, string $rejectReason): LeaveRequest
    {
        $leaveRequest->update([
            'status' => 'rejected',
            'reject_reason' => $rejectReason,
            'decision_at' => now(),
        ]);

        event(new LeaveRequestRejected($leaveRequest));

        return $leaveRequest->fresh();
    }

    public function update(LeaveRequest $leaveRequest, array $data): LeaveRequest
    {
        $leaveRequest->update([
            'leave_type_id' => $data['leave_type_id'],
            'start_date'    => $data['start_date'],
            'end_date'      => $data['end_date'],
            'reason'        => $data['reason'],
        ]);

        return $leaveRequest->fresh();
    }

    public function delete(LeaveRequest $leaveRequest): void
    {
        $leaveRequest->delete();
    }

    public function getPendingForUser(int $userId): \Illuminate\Database\Eloquent\Collection
    {
        return LeaveRequest::with('leaveType')
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->latest()
            ->get();
    }

    public function getStatsForUser(int $userId): array
    {
        return [
            'totalRequests'    => LeaveRequest::where('user_id', $userId)->count(),
            'pendingRequests'  => LeaveRequest::where('user_id', $userId)->where('status', 'pending')->count(),
            'approvedRequests' => LeaveRequest::where('user_id', $userId)->where('status', 'approved')->count(),
            'rejectedRequests' => LeaveRequest::where('user_id', $userId)->where('status', 'rejected')->count(),
        ];
    }

    public function getAdminStats(): array
    {
        return [
            'totalEmployees'   => \App\Models\User::where('role', 'karyawan')->count(),
            'totalRequests'    => LeaveRequest::count(),
            'pendingRequests'  => LeaveRequest::where('status', 'pending')->count(),
            'approvedRequests' => LeaveRequest::where('status', 'approved')->count(),
            'rejectedRequests' => LeaveRequest::where('status', 'rejected')->count(),
        ];
    }
}
