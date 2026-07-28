<?php

namespace App\Services;

use App\Models\LeaveType;
use Illuminate\Database\Eloquent\Collection;

class LeaveTypeService
{
    public function getAll(): Collection
    {
        return LeaveType::latest()->get();
    }

    public function getById(int $id): LeaveType
    {
        return LeaveType::findOrFail($id);
    }

    public function create(array $data): LeaveType
    {
        return LeaveType::create($data);
    }

    public function update(LeaveType $leaveType, array $data): LeaveType
    {
        $leaveType->update($data);

        return $leaveType->fresh();
    }

    public function delete(LeaveType $leaveType): void
    {
        $leaveType->delete();
    }
}
