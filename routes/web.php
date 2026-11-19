<?php

use App\Http\Controllers\Auth\ForcePasswordChangeController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\PartnershipApplicationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\CertificateController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'companies'])->name('landing.home');
Route::get('/mahasiswa', [LandingController::class, 'students'])->name('landing.students');
Route::get('/perusahaan', [LandingController::class, 'companies'])->name('landing.companies');

Route::get('/kebijakan-privasi', function () {
    return view('privacy-policy');
})->name('privacy-policy');

// ponytail: shared hosting has no SSH, so `storage:link` can't be run there — serve public storage files
// directly as a fallback when no symlink exists. Harmless on hosts that do have the real symlink.
Route::get('/storage/{path}', function (string $path) {
    $file = storage_path('app/public/'.$path);
    abort_unless(is_file($file), 404);

    return response()->file($file);
})->where('path', '.*')->name('storage.fallback');

Route::post('/perusahaan/kemitraan', [PartnershipApplicationController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('partnership.store');

Route::middleware('auth')->group(function () {
    Route::get('/password/change', [ForcePasswordChangeController::class, 'show'])->name('password.change');
    Route::post('/password/change', [ForcePasswordChangeController::class, 'update'])->name('password.change.update');
});

Route::middleware(['auth', 'password.changed'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Shared by student (owner) and admin per business rule 8 — gated by CertificatePolicy, not role.
    Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');

    // Shared by all 3 roles — the bell dropdown lives in the common dashboard layout.
    Route::post('/notifications/mark-read', function (\Illuminate\Http\Request $request) {
        $request->user()->unreadNotifications->markAsRead();

        return back();
    })->name('notifications.mark-read');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/student.php';
require __DIR__.'/company.php';
