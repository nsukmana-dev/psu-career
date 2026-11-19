<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ApprovalStatus;
use App\Enums\CompanyStatus;
use App\Enums\ItemStatus;
use App\Enums\RequestStatus;
use App\Enums\VerificationStatus;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\ContactRequest;
use App\Models\ContactRequestItem;
use App\Models\PartnershipApplication;
use App\Models\Student;
use App\Models\StudentUpdateRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $statusCounts = DB::table('students')
            ->selectRaw('verification_status, count(*) as total')
            ->groupBy('verification_status')
            ->pluck('total', 'verification_status');

        $queue = [
            ['label' => 'Verifikasi profil', 'count' => Student::where('verification_status', VerificationStatus::Pending)->count(), 'route' => 'admin.verifications.index'],
            ['label' => 'Update semester', 'count' => StudentUpdateRequest::where('status', ApprovalStatus::Pending)->count(), 'route' => 'admin.update-requests.index'],
            ['label' => 'Pengajuan kemitraan', 'count' => PartnershipApplication::where('status', ApprovalStatus::Pending)->count(), 'route' => 'admin.partnership-applications.index'],
            ['label' => 'Request kontak', 'count' => ContactRequest::where('status', RequestStatus::Pending)->count(), 'route' => 'admin.contact-requests.index'],
        ];

        $registrations = collect(range(5, 0))->map(function (int $i) {
            $month = now()->subMonths($i);

            return [
                'label' => $month->translatedFormat('M Y'),
                'count' => Student::whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)->count(),
            ];
        });

        return view('admin.dashboard', [
            'statusCounts' => $statusCounts,
            'companiesActive' => Company::where('status', CompanyStatus::Active)->count(),
            'contactsThisMonth' => ContactRequestItem::where('status', ItemStatus::Approved)
                ->whereYear('decided_at', now()->year)
                ->whereMonth('decided_at', now()->month)
                ->count(),
            'queue' => $queue,
            'registrations' => $registrations,
        ]);
    }
}
