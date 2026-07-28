<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use App\Services\LeaveTypeService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LeaveTypeController extends Controller
{
    use AuthorizesRequests;

    protected LeaveTypeService $leaveTypeService;

    public function __construct(LeaveTypeService $leaveTypeService)
    {
        $this->leaveTypeService = $leaveTypeService;
    }

    public function index()
    {
        $leaveTypes = $this->leaveTypeService->getAll();

        return view('leave-types.index', compact('leaveTypes'));
    }

    public function create()
    {
        return view('leave-types.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', LeaveType::class);

        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
        ]);

        $this->leaveTypeService->create($request->only(['name', 'description']));

        return redirect()
            ->route('leave-types.index')
            ->with('success', 'Jenis cuti berhasil ditambahkan.');
    }

    public function show(LeaveType $leaveType)
    {
        $this->authorize('view', $leaveType);

        return view('leave-types.show', compact('leaveType'));
    }

    public function edit(LeaveType $leaveType)
    {
        $this->authorize('update', $leaveType);

        return view('leave-types.edit', compact('leaveType'));
    }

    public function update(Request $request, LeaveType $leaveType)
    {
        $this->authorize('update', $leaveType);

        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
        ]);

        $this->leaveTypeService->update($leaveType, $request->only(['name', 'description']));

        return redirect()
            ->route('leave-types.index')
            ->with('success', 'Jenis cuti berhasil diperbarui.');
    }

    public function destroy(LeaveType $leaveType)
    {
        $this->authorize('delete', $leaveType);

        $this->leaveTypeService->delete($leaveType);

        return redirect()
            ->route('leave-types.index')
            ->with('success', 'Jenis cuti berhasil dihapus.');
    }
}
