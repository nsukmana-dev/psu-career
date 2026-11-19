<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ApprovalStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DecideUpdateRequestRequest;
use App\Models\StudentUpdateRequest;
use App\Models\StudyProgram;
use App\Services\StudentUpdateRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudentUpdateRequestController extends Controller
{
    private const FIELD_LABELS = [
        'current_semester' => 'Semester',
        'gpa' => 'IPK',
        'study_program_id' => 'Program Studi',
        'entry_year' => 'Angkatan',
    ];

    public function index(): View
    {
        $requests = StudentUpdateRequest::query()
            ->with(['student.user'])
            ->where('status', ApprovalStatus::Pending)
            ->orderBy('created_at')
            ->paginate(10)
            ->withQueryString();

        $programNames = StudyProgram::pluck('name', 'id');

        return view('admin.update-requests.index', [
            'requests' => $requests,
            'programNames' => $programNames,
            'fieldLabels' => self::FIELD_LABELS,
            'decidedThisMonth' => StudentUpdateRequest::where('status', '!=', ApprovalStatus::Pending)
                ->whereMonth('reviewed_at', now()->month)->whereYear('reviewed_at', now()->year)->count(),
        ]);
    }

    public function show(StudentUpdateRequest $updateRequest): View
    {
        $updateRequest->load(['student.user', 'student.studyProgram']);

        return view('admin.update-requests.show', ['updateRequest' => $updateRequest]);
    }

    public function approve(
        StudentUpdateRequest $updateRequest,
        DecideUpdateRequestRequest $request,
        StudentUpdateRequestService $service
    ): RedirectResponse {
        $service->approve($updateRequest, $request->user(), $request->note);

        return redirect()->route('admin.update-requests.index')->with('status', 'Perubahan data disetujui.');
    }

    public function reject(
        StudentUpdateRequest $updateRequest,
        DecideUpdateRequestRequest $request,
        StudentUpdateRequestService $service
    ): RedirectResponse {
        $service->reject($updateRequest, $request->user(), $request->note);

        return redirect()->route('admin.update-requests.index')->with('status', 'Perubahan data ditolak.');
    }
}
