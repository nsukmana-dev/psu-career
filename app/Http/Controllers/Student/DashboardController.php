<?php

namespace App\Http\Controllers\Student;

use App\Enums\ItemStatus;
use App\Http\Controllers\Controller;
use App\Models\ProfileView;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $student = $request->user()->student;

        return view('student.dashboard', [
            'student' => $student,
            'profileViewsThisMonth' => ProfileView::where('student_id', $student->id)
                ->whereMonth('viewed_at', now()->month)
                ->whereYear('viewed_at', now()->year)
                ->distinct('company_id')
                ->count('company_id'),
            'pendingUpdateRequest' => $student->updateRequests->firstWhere('status', 'pending'),
            'contactHistoryCount' => $student->contactRequestItems()->where('status', ItemStatus::Approved)->count(),
        ]);
    }
}
