<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Services\LeaveRequestService;
use App\Services\LeaveBalanceService;
use App\Services\LeaveTypeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LeaveRequestController extends Controller
{
    use AuthorizesRequests;

    protected LeaveRequestService $requestService;
    protected LeaveBalanceService $balanceService;

    public function __construct(LeaveRequestService $requestService, LeaveBalanceService $balanceService)
    {
        $this->requestService = $requestService;
        $this->balanceService = $balanceService;
    }

    public function index()
    {
        $leaveRequests = $this->requestService->getForUser(auth()->id());

        return view('leave-requests.index', compact('leaveRequests'));
    }

    public function create()
    {
        $leaveTypes = LeaveType::all();
        $leaveBalances = $this->balanceService->getBalancesForUser(auth()->id());

        return view('leave-requests.create', compact('leaveTypes', 'leaveBalances'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'reason'        => 'required|string',
            'attachment'    => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:102400',
        ]);

        $data = $request->all();

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }

        try {
            $this->requestService->create($data, Auth::id());
        } catch (\App\Exceptions\InsufficientLeaveBalanceException $e) {
            return back()->withErrors([
                'leave_type_id' => $e->getMessage(),
            ])->withInput();
        }

        return redirect()
            ->route('leave-requests.index')
            ->with('success', 'Pengajuan cuti berhasil dikirim.');
    }

    public function show(LeaveRequest $leaveRequest)
    {
        $this->authorize('view', $leaveRequest);

        $leaveRequest->load(['leaveType', 'user']);

        return view('leave-requests.show', compact('leaveRequest'));
    }

    public function edit(LeaveRequest $leaveRequest)
    {
        $this->authorize('update', $leaveRequest);

        $leaveTypes = LeaveType::all();
        $leaveBalances = $this->balanceService->getBalancesForUser(auth()->id());

        return view('leave-requests.edit', compact('leaveRequest', 'leaveTypes', 'leaveBalances'));
    }

    public function update(Request $request, LeaveRequest $leaveRequest)
    {
        $this->authorize('update', $leaveRequest);

        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'reason'        => 'required|string',
            'attachment'    => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:102400',
        ]);

        $data = $request->all();

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }

        $this->requestService->update($leaveRequest, $data);

        return redirect()
            ->route('leave-requests.index')
            ->with('success', 'Pengajuan cuti berhasil diperbarui.');
    }

    public function destroy(LeaveRequest $leaveRequest)
    {
        $this->authorize('delete', $leaveRequest);

        $this->requestService->delete($leaveRequest);

        return redirect()
            ->route('leave-requests.index')
            ->with('success', 'Pengajuan cuti berhasil dihapus.');
    }

    public function downloadAttachment(LeaveRequest $leaveRequest)
    {
        $this->authorize('view', $leaveRequest);

        if (!$leaveRequest->attachment) {
            abort(404, 'Lampiran tidak ditemukan.');
        }

        $path = storage_path('app/public/' . $leaveRequest->attachment);

        if (!file_exists($path)) {
            abort(404, 'File lampiran tidak ditemukan.');
        }

        return response()->download($path);
    }
}
