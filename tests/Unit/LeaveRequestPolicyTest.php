<?php

use App\Models\User;
use App\Models\LeaveType;
use App\Models\LeaveRequest;
use App\Policies\LeaveRequestPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->karyawan = User::factory()->create(['role' => 'karyawan']);
    $this->leaveType = LeaveType::create(['name' => 'Cuti Tahunan']);
    $this->policy = new LeaveRequestPolicy();
});

test('karyawan can view own leave request', function () {
    $leaveRequest = LeaveRequest::create([
        'user_id' => $this->karyawan->id,
        'leave_type_id' => $this->leaveType->id,
        'start_date' => '2026-09-01',
        'end_date' => '2026-09-03',
        'reason' => 'Liburan',
        'status' => 'pending',
    ]);

    $this->assertTrue($this->policy->view($this->karyawan, $leaveRequest));
});

test('karyawan cannot view other karyawan leave request', function () {
    $otherKaryawan = User::factory()->create(['role' => 'karyawan']);

    $leaveRequest = LeaveRequest::create([
        'user_id' => $this->karyawan->id,
        'leave_type_id' => $this->leaveType->id,
        'start_date' => '2026-09-01',
        'end_date' => '2026-09-03',
        'reason' => 'Liburan',
        'status' => 'pending',
    ]);

    $this->assertFalse($this->policy->view($otherKaryawan, $leaveRequest));
});

test('karyawan can create leave request', function () {
    $this->assertTrue($this->policy->create($this->karyawan));
});

test('admin cannot create leave request', function () {
    $this->assertFalse($this->policy->create($this->admin));
});

test('karyawan can update pending own leave request', function () {
    $leaveRequest = LeaveRequest::create([
        'user_id' => $this->karyawan->id,
        'leave_type_id' => $this->leaveType->id,
        'start_date' => '2026-09-01',
        'end_date' => '2026-09-03',
        'reason' => 'Liburan',
        'status' => 'pending',
    ]);

    $this->assertTrue($this->policy->update($this->karyawan, $leaveRequest));
});

test('karyawan cannot update approved leave request', function () {
    $leaveRequest = LeaveRequest::create([
        'user_id' => $this->karyawan->id,
        'leave_type_id' => $this->leaveType->id,
        'start_date' => '2026-09-01',
        'end_date' => '2026-09-03',
        'reason' => 'Liburan',
        'status' => 'approved',
    ]);

    $this->assertFalse($this->policy->update($this->karyawan, $leaveRequest));
});

test('admin can approve pending leave request', function () {
    $leaveRequest = LeaveRequest::create([
        'user_id' => $this->karyawan->id,
        'leave_type_id' => $this->leaveType->id,
        'start_date' => '2026-09-01',
        'end_date' => '2026-09-03',
        'reason' => 'Liburan',
        'status' => 'pending',
    ]);

    $this->assertTrue($this->policy->approve($this->admin, $leaveRequest));
});

test('karyawan cannot approve leave request', function () {
    $leaveRequest = LeaveRequest::create([
        'user_id' => $this->karyawan->id,
        'leave_type_id' => $this->leaveType->id,
        'start_date' => '2026-09-01',
        'end_date' => '2026-09-03',
        'reason' => 'Liburan',
        'status' => 'pending',
    ]);

    $this->assertFalse($this->policy->approve($this->karyawan, $leaveRequest));
});

test('admin can reject pending leave request', function () {
    $leaveRequest = LeaveRequest::create([
        'user_id' => $this->karyawan->id,
        'leave_type_id' => $this->leaveType->id,
        'start_date' => '2026-09-01',
        'end_date' => '2026-09-03',
        'reason' => 'Liburan',
        'status' => 'pending',
    ]);

    $this->assertTrue($this->policy->reject($this->admin, $leaveRequest));
});

test('admin cannot approve already approved leave request', function () {
    $leaveRequest = LeaveRequest::create([
        'user_id' => $this->karyawan->id,
        'leave_type_id' => $this->leaveType->id,
        'start_date' => '2026-09-01',
        'end_date' => '2026-09-03',
        'reason' => 'Liburan',
        'status' => 'approved',
    ]);

    $this->assertFalse($this->policy->approve($this->admin, $leaveRequest));
});
