<?php

use App\Models\User;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Services\LeaveBalanceService;
use App\Exceptions\InsufficientLeaveBalanceException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = new LeaveBalanceService();
    $this->user = User::factory()->create(['role' => 'karyawan']);
    $this->leaveType = LeaveType::create(['name' => 'Cuti Tahunan']);
});

test('get balance returns correct balance', function () {
    LeaveBalance::create([
        'user_id' => $this->user->id,
        'leave_type_id' => $this->leaveType->id,
        'quota' => 12,
        'used' => 5,
        'year' => date('Y'),
    ]);

    $balance = $this->service->getBalance($this->user->id, $this->leaveType->id);

    $this->assertNotNull($balance);
    $this->assertEquals(12, $balance->quota);
    $this->assertEquals(5, $balance->used);
});

test('get remaining days calculates correctly', function () {
    LeaveBalance::create([
        'user_id' => $this->user->id,
        'leave_type_id' => $this->leaveType->id,
        'quota' => 12,
        'used' => 5,
        'year' => date('Y'),
    ]);

    $remaining = $this->service->getRemainingDays($this->user->id, $this->leaveType->id);

    $this->assertEquals(7, $remaining);
});

test('check and deduct balance reduces used count', function () {
    LeaveBalance::create([
        'user_id' => $this->user->id,
        'leave_type_id' => $this->leaveType->id,
        'quota' => 12,
        'used' => 0,
        'year' => date('Y'),
    ]);

    $balance = $this->service->checkAndDeductBalance(
        $this->user->id,
        $this->leaveType->id,
        3
    );

    $this->assertEquals(3, $balance->used);
    $this->assertEquals(9, $balance->remaining);
});

test('check and deduct throws exception when insufficient balance', function () {
    LeaveBalance::create([
        'user_id' => $this->user->id,
        'leave_type_id' => $this->leaveType->id,
        'quota' => 12,
        'used' => 10,
        'year' => date('Y'),
    ]);

    $this->expectException(InsufficientLeaveBalanceException::class);

    $this->service->checkAndDeductBalance(
        $this->user->id,
        $this->leaveType->id,
        5
    );
});

test('calculate days returns correct count', function () {
    $start = \Carbon\Carbon::parse('2026-09-01');
    $end = \Carbon\Carbon::parse('2026-09-03');

    $days = $this->service->calculateDays($start, $end);

    $this->assertEquals(3, $days);
});

test('restore balance reduces used count', function () {
    LeaveBalance::create([
        'user_id' => $this->user->id,
        'leave_type_id' => $this->leaveType->id,
        'quota' => 12,
        'used' => 5,
        'year' => date('Y'),
    ]);

    $this->service->restoreBalance($this->user->id, $this->leaveType->id, 2);

    $balance = $this->service->getBalance($this->user->id, $this->leaveType->id);
    $this->assertEquals(3, $balance->used);
});

test('get balances for user returns all balances', function () {
    $leaveType2 = LeaveType::create(['name' => 'Cuti Sakit']);

    LeaveBalance::create([
        'user_id' => $this->user->id,
        'leave_type_id' => $this->leaveType->id,
        'quota' => 12,
        'used' => 0,
        'year' => date('Y'),
    ]);

    LeaveBalance::create([
        'user_id' => $this->user->id,
        'leave_type_id' => $leaveType2->id,
        'quota' => 5,
        'used' => 0,
        'year' => date('Y'),
    ]);

    $balances = $this->service->getBalancesForUser($this->user->id);

    $this->assertCount(2, $balances);
});
