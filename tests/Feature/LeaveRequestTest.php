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
    LeaveBalance::create([
        'user_id' => $this->karyawan->id,
        'leave_type_id' => $this->leaveType->id,
        'quota' => 12,
        'used' => 0,
        'year' => date('Y'),
    ]);
});

test('karyawan can view leave requests index', function () {
    $this->actingAs($this->karyawan);

    $response = $this->get(route('leave-requests.index'));

    $response->assertStatus(200);
});

test('karyawan can create leave request', function () {
    $this->actingAs($this->karyawan);

    $response = $this->post(route('leave-requests.store'), [
        'leave_type_id' => $this->leaveType->id,
        'start_date' => '2026-09-01',
        'end_date' => '2026-09-03',
        'reason' => 'Liburan keluarga',
    ]);

    $response->assertRedirect(route('leave-requests.index'));
    $this->assertDatabaseHas('leave_requests', [
        'user_id' => $this->karyawan->id,
        'leave_type_id' => $this->leaveType->id,
        'status' => 'pending',
    ]);
});

test('karyawan cannot create leave request without required fields', function () {
    $this->actingAs($this->karyawan);

    $response = $this->post(route('leave-requests.store'), [
        'leave_type_id' => '',
        'start_date' => '',
        'end_date' => '',
        'reason' => '',
    ]);

    $response->assertSessionHasErrors(['leave_type_id', 'start_date', 'end_date', 'reason']);
});

test('karyawan can view own leave request detail', function () {
    $this->actingAs($this->karyawan);

    $leaveRequest = LeaveRequest::create([
        'user_id' => $this->karyawan->id,
        'leave_type_id' => $this->leaveType->id,
        'start_date' => '2026-09-01',
        'end_date' => '2026-09-03',
        'reason' => 'Liburan',
        'status' => 'pending',
    ]);

    $response = $this->get(route('leave-requests.show', $leaveRequest));

    $response->assertStatus(200);
});

test('karyawan cannot view other karyawan leave request', function () {
    $otherKaryawan = User::factory()->create(['role' => 'karyawan']);
    $this->actingAs($otherKaryawan);

    $leaveRequest = LeaveRequest::create([
        'user_id' => $this->karyawan->id,
        'leave_type_id' => $this->leaveType->id,
        'start_date' => '2026-09-01',
        'end_date' => '2026-09-03',
        'reason' => 'Liburan',
        'status' => 'pending',
    ]);

    $response = $this->get(route('leave-requests.show', $leaveRequest));

    $response->assertStatus(403);
});

test('karyawan can delete pending leave request', function () {
    $this->actingAs($this->karyawan);

    $leaveRequest = LeaveRequest::create([
        'user_id' => $this->karyawan->id,
        'leave_type_id' => $this->leaveType->id,
        'start_date' => '2026-09-01',
        'end_date' => '2026-09-03',
        'reason' => 'Liburan',
        'status' => 'pending',
    ]);

    $response = $this->delete(route('leave-requests.destroy', $leaveRequest));

    $response->assertRedirect(route('leave-requests.index'));
    $this->assertDatabaseMissing('leave_requests', ['id' => $leaveRequest->id]);
});

test('karyawan cannot delete approved leave request', function () {
    $this->actingAs($this->karyawan);

    $leaveRequest = LeaveRequest::create([
        'user_id' => $this->karyawan->id,
        'leave_type_id' => $this->leaveType->id,
        'start_date' => '2026-09-01',
        'end_date' => '2026-09-03',
        'reason' => 'Liburan',
        'status' => 'approved',
    ]);

    $response = $this->delete(route('leave-requests.destroy', $leaveRequest));

    $response->assertStatus(403);
});
