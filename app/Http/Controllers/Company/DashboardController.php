<?php

namespace App\Http\Controllers\Company;

use App\Enums\ItemStatus;
use App\Enums\RequestStatus;
use App\Http\Controllers\Controller;
use App\Models\ContactRequestItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $company = $request->user()->company;

        return view('company.dashboard', [
            'company' => $company,
            'shortlistsCount' => $company->shortlists()->count(),
            'requestsPending' => $company->contactRequests()->where('status', RequestStatus::Pending)->count(),
            'requestsCompleted' => $company->contactRequests()->where('status', RequestStatus::Completed)->count(),
            'recentContacts' => ContactRequestItem::where('status', ItemStatus::Approved)
                ->whereHas('contactRequest', fn ($q) => $q->where('company_id', $company->id))
                ->with(['student.user', 'student.studyProgram'])
                ->orderByDesc('decided_at')
                ->take(5)
                ->get(),
            'savedSearches' => $company->savedSearches()->latest()->take(5)->get(),
        ]);
    }
}
