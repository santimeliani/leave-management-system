<?php

namespace App\Services;

use App\Models\LeaveBalance;
use App\Models\LeaveRequest;
use App\Exceptions\InsufficientLeaveBalanceException;

class LeaveBalanceService
{
    public function getBalance(int $userId, int $leaveTypeId, int $year = null): ?LeaveBalance
    {
        $year = $year ?? (int) date('Y');

        return LeaveBalance::where('user_id', $userId)
            ->where('leave_type_id', $leaveTypeId)
            ->where('year', $year)
            ->first();
    }

    public function getBalancesForUser(int $userId, int $year = null): \Illuminate\Database\Eloquent\Collection
    {
        $year = $year ?? (int) date('Y');

        return LeaveBalance::with('leaveType')
            ->where('user_id', $userId)
            ->where('year', $year)
            ->get();
    }

    public function getRemainingDays(int $userId, int $leaveTypeId, int $year = null): int
    {
        $balance = $this->getBalance($userId, $leaveTypeId, $year);

        return $balance ? $balance->remaining : 0;
    }

    public function checkAndDeductBalance(int $userId, int $leaveTypeId, int $days, int $year = null): LeaveBalance
    {
        $year = $year ?? (int) date('Y');
        $balance = $this->getBalance($userId, $leaveTypeId, $year);

        if (!$balance || $balance->remaining < $days) {
            $remaining = $balance ? $balance->remaining : 0;
            throw new InsufficientLeaveBalanceException(
                "Kuota cuti tidak mencukupi. Sisa kuota: {$remaining} hari, diperlukan: {$days} hari."
            );
        }

        $balance->increment('used', $days);

        return $balance->fresh();
    }

    public function deductBalance(int $userId, int $leaveTypeId, int $days, int $year = null): void
    {
        $year = $year ?? (int) date('Y');
        $balance = $this->getBalance($userId, $leaveTypeId, $year);

        if ($balance) {
            $balance->increment('used', $days);
        }
    }

    public function restoreBalance(int $userId, int $leaveTypeId, int $days, int $year = null): void
    {
        $year = $year ?? (int) date('Y');
        $balance = $this->getBalance($userId, $leaveTypeId, $year);

        if ($balance) {
            $balance->decrement('used', $days);
        }
    }

    public function calculateDays(\Carbon\Carbon $startDate, \Carbon\Carbon $endDate): int
    {
        return $startDate->diffInDays($endDate) + 1;
    }
}
