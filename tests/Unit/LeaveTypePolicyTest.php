<?php

use App\Models\User;
use App\Models\LeaveType;
use App\Policies\LeaveTypePolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->karyawan = User::factory()->create(['role' => 'karyawan']);
    $this->leaveType = LeaveType::create(['name' => 'Cuti Tahunan']);
    $this->policy = new LeaveTypePolicy();
});

test('admin can view leave types', function () {
    $this->assertTrue($this->policy->viewAny($this->admin));
});

test('karyawan cannot view leave types', function () {
    $this->assertFalse($this->policy->viewAny($this->karyawan));
});

test('admin can view single leave type', function () {
    $this->assertTrue($this->policy->view($this->admin, $this->leaveType));
});

test('karyawan cannot view single leave type', function () {
    $this->assertFalse($this->policy->view($this->karyawan, $this->leaveType));
});

test('admin can create leave type', function () {
    $this->assertTrue($this->policy->create($this->admin));
});

test('karyawan cannot create leave type', function () {
    $this->assertFalse($this->policy->create($this->karyawan));
});

test('admin can update leave type', function () {
    $this->assertTrue($this->policy->update($this->admin, $this->leaveType));
});

test('karyawan cannot update leave type', function () {
    $this->assertFalse($this->policy->update($this->karyawan, $this->leaveType));
});

test('admin can delete leave type', function () {
    $this->assertTrue($this->policy->delete($this->admin, $this->leaveType));
});

test('karyawan cannot delete leave type', function () {
    $this->assertFalse($this->policy->delete($this->karyawan, $this->leaveType));
});
