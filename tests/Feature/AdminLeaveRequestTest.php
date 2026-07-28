<?php

use App\Models\User;
use App\Models\LeaveType;
use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->karyawan = User::factory()->create(['role' => 'karyawan']);
    $this->leaveType = LeaveType::create(['name' => 'Cuti Tahunan', 'description' => 'Cuti tahunan']);
    $this->balance = LeaveBalance::create([
        'user_id' => $this->karyawan->id,
        'leave_type_id' => $this->leaveType->id,
        'quota' => 12,
        'used' => 0,
        'year' => date('Y'),
    ]);
});

test('admin can view all leave requests', function () {
    $this->actingAs($this->admin);

    $response = $this->get(route('admin.leave-requests.index'));

    $response->assertStatus(200);
});

test('admin can approve leave request', function () {
    $this->actingAs($this->admin);

    $leaveRequest = LeaveRequest::create([
        'user_id' => $this->karyawan->id,
        'leave_type_id' => $this->leaveType->id,
        'start_date' => '2026-09-01',
        'end_date' => '2026-09-03',
        'reason' => 'Liburan',
        'status' => 'pending',
    ]);

    $response = $this->post(route('admin.leave-requests.approve', $leaveRequest));

    $response->assertRedirect(route('admin.leave-requests.index'));

    $leaveRequest->refresh();
    $this->assertEquals('approved', $leaveRequest->status);
    $this->assertNotNull($leaveRequest->decision_at);
});

test('admin approval deducts leave balance', function () {
    $this->actingAs($this->admin);

    $leaveRequest = LeaveRequest::create([
        'user_id' => $this->karyawan->id,
        'leave_type_id' => $this->leaveType->id,
        'start_date' => '2026-09-01',
        'end_date' => '2026-09-03',
        'reason' => 'Liburan',
        'status' => 'pending',
    ]);

    $this->post(route('admin.leave-requests.approve', $leaveRequest));

    $this->balance->refresh();
    $this->assertEquals(3, $this->balance->used);
    $this->assertEquals(9, $this->balance->remaining);
});

test('admin can reject leave request with reason', function () {
    $this->actingAs($this->admin);

    $leaveRequest = LeaveRequest::create([
        'user_id' => $this->karyawan->id,
        'leave_type_id' => $this->leaveType->id,
        'start_date' => '2026-09-01',
        'end_date' => '2026-09-03',
        'reason' => 'Liburan',
        'status' => 'pending',
    ]);

    $response = $this->post(route('admin.leave-requests.reject', $leaveRequest), [
        'reject_reason' => 'Masalah operasional',
    ]);

    $response->assertRedirect(route('admin.leave-requests.index'));

    $leaveRequest->refresh();
    $this->assertEquals('rejected', $leaveRequest->status);
    $this->assertEquals('Masalah operasional', $leaveRequest->reject_reason);
    $this->assertNotNull($leaveRequest->decision_at);
});

test('admin rejection without reason fails validation', function () {
    $this->actingAs($this->admin);

    $leaveRequest = LeaveRequest::create([
        'user_id' => $this->karyawan->id,
        'leave_type_id' => $this->leaveType->id,
        'start_date' => '2026-09-01',
        'end_date' => '2026-09-03',
        'reason' => 'Liburan',
        'status' => 'pending',
    ]);

    $response = $this->post(route('admin.leave-requests.reject', $leaveRequest), [
        'reject_reason' => '',
    ]);

    $response->assertSessionHasErrors(['reject_reason']);
});

test('karyawan cannot approve leave request', function () {
    $this->actingAs($this->karyawan);

    $leaveRequest = LeaveRequest::create([
        'user_id' => $this->karyawan->id,
        'leave_type_id' => $this->leaveType->id,
        'start_date' => '2026-09-01',
        'end_date' => '2026-09-03',
        'reason' => 'Liburan',
        'status' => 'pending',
    ]);

    $response = $this->post(route('admin.leave-requests.approve', $leaveRequest));

    $response->assertStatus(403);
});

test('cannot approve already approved request', function () {
    $this->actingAs($this->admin);

    $leaveRequest = LeaveRequest::create([
        'user_id' => $this->karyawan->id,
        'leave_type_id' => $this->leaveType->id,
        'start_date' => '2026-09-01',
        'end_date' => '2026-09-03',
        'reason' => 'Liburan',
        'status' => 'approved',
    ]);

    $response = $this->post(route('admin.leave-requests.approve', $leaveRequest));

    $response->assertStatus(403);
});
