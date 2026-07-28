<?php

use App\Models\User;
use App\Models\LeaveType;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create(['role' => 'admin']);
    $this->karyawan = User::factory()->create(['role' => 'karyawan']);
});

test('admin can view leave types index', function () {
    $this->actingAs($this->admin);

    $response = $this->get(route('leave-types.index'));

    $response->assertStatus(200);
});

test('admin can create leave type', function () {
    $this->actingAs($this->admin);

    $response = $this->post(route('leave-types.store'), [
        'name' => 'Cuti Sakit',
        'description' => 'Cuti untuk karyawan yang sakit',
    ]);

    $response->assertRedirect(route('leave-types.index'));
    $this->assertDatabaseHas('leave_types', ['name' => 'Cuti Sakit']);
});

test('admin can update leave type', function () {
    $this->actingAs($this->admin);

    $leaveType = LeaveType::create(['name' => 'Cuti Lama']);

    $response = $this->put(route('leave-types.update', $leaveType), [
        'name' => 'Cuti Baru',
        'description' => 'Deskripsi baru',
    ]);

    $response->assertRedirect(route('leave-types.index'));
    $this->assertDatabaseHas('leave_types', ['id' => $leaveType->id, 'name' => 'Cuti Baru']);
});

test('admin can delete leave type', function () {
    $this->actingAs($this->admin);

    $leaveType = LeaveType::create(['name' => 'Cuti Hapus']);

    $response = $this->delete(route('leave-types.destroy', $leaveType));

    $response->assertRedirect(route('leave-types.index'));
    $this->assertDatabaseMissing('leave_types', ['id' => $leaveType->id]);
});

test('karyawan cannot access leave types', function () {
    $this->actingAs($this->karyawan);

    $response = $this->get(route('leave-types.index'));

    $response->assertStatus(403);
});

test('karyawan cannot create leave type', function () {
    $this->actingAs($this->karyawan);

    $response = $this->post(route('leave-types.store'), [
        'name' => 'Cuti Ilegal',
    ]);

    $response->assertStatus(403);
});

test('leave type requires name', function () {
    $this->actingAs($this->admin);

    $response = $this->post(route('leave-types.store'), [
        'name' => '',
    ]);

    $response->assertSessionHasErrors(['name']);
});
