<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\AdminLeaveRequestController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\KaryawanDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/debug', function () {
    return response()->json([
        'config_app_url' => config('app.url'),
        'env_app_url' => env('APP_URL'),
        'scheme' => request()->getScheme(),
        'is_secure' => request()->isSecure(),
        'env' => app()->environment(),
    ]);
});

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('karyawan.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/leave-requests/{leaveRequest}/attachment',
        [LeaveRequestController::class, 'downloadAttachment'])
        ->name('leave-requests.attachment');

});

require __DIR__.'/auth.php';

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin/dashboard', AdminDashboardController::class)
        ->name('admin.dashboard');

    Route::resource('leave-types', LeaveTypeController::class);

    Route::resource('admin/leave-requests', AdminLeaveRequestController::class)
        ->only(['index'])
        ->names([
            'index' => 'admin.leave-requests.index'
        ]);

    Route::post('/admin/leave-requests/{leaveRequest}/approve',
        [AdminLeaveRequestController::class, 'approve'])
        ->name('admin.leave-requests.approve');

    Route::get('/admin/leave-requests/{leaveRequest}/reject',
        [AdminLeaveRequestController::class, 'showRejectForm'])
        ->name('admin.leave-requests.reject.form');

    Route::post('/admin/leave-requests/{leaveRequest}/reject',
        [AdminLeaveRequestController::class, 'reject'])
        ->name('admin.leave-requests.reject');

});

Route::middleware(['auth', 'role:karyawan'])->group(function () {

    Route::get('/karyawan/dashboard', KaryawanDashboardController::class)
        ->name('karyawan.dashboard');

    Route::resource('leave-requests', LeaveRequestController::class);

});
