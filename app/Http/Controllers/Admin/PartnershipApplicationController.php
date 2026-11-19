<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ApprovalStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RejectPartnershipRequest;
use App\Models\ActivityLog;
use App\Models\PartnershipApplication;
use App\Notifications\PartnershipApplicationRejected;
use App\Services\CompanyOnboardingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class PartnershipApplicationController extends Controller
{
    public function index(): View
    {
        $applications = PartnershipApplication::query()
            ->with('industry')
            ->where('status', ApprovalStatus::Pending)
            ->orderBy('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.partnership-applications.index', [
            'applications' => $applications,
            'decidedThisMonth' => PartnershipApplication::where('status', '!=', ApprovalStatus::Pending)
                ->whereMonth('reviewed_at', now()->month)->whereYear('reviewed_at', now()->year)->count(),
        ]);
    }

    public function show(PartnershipApplication $application): View
    {
        return view('admin.partnership-applications.show', ['application' => $application->load('industry')]);
    }

    public function approve(
        PartnershipApplication $application,
        Request $request,
        CompanyOnboardingService $onboarding
    ): RedirectResponse {
        $company = $onboarding->createFromApplication($application);

        $application->update([
            'status' => ApprovalStatus::Approved,
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
            'company_id' => $company->id,
        ]);

        ActivityLog::record('partnership.approved', $application);

        return redirect()->route('admin.partnership-applications.index')
            ->with('status', 'Pengajuan disetujui, akun perusahaan dibuat dan email undangan terkirim.');
    }

    public function reject(PartnershipApplication $application, RejectPartnershipRequest $request): RedirectResponse
    {
        $application->update([
            'status' => ApprovalStatus::Rejected,
            'reviewed_by' => $request->user()->id,
            'reviewed_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        Notification::route('mail', $application->email)
            ->notify(new PartnershipApplicationRejected($application));

        ActivityLog::record('partnership.rejected', $application, ['reason' => $request->rejection_reason]);

        return redirect()->route('admin.partnership-applications.index')->with('status', 'Pengajuan ditolak.');
    }
}
