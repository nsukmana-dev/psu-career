<?php

namespace App\Http\Controllers\Admin;

use App\Enums\VerificationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RejectStudentRequest;
use App\Models\Student;
use App\Services\StudentVerificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerificationController extends Controller
{
    public function index(Request $request): View
    {
        $students = Student::query()
            ->with(['user', 'studyProgram', 'skills'])
            ->where('verification_status', VerificationStatus::Pending)
            ->when($request->search, fn ($q, $term) => $q->where(fn ($q2) => $q2
                ->where('nim', 'like', "%{$term}%")
                ->orWhereHas('user', fn ($q3) => $q3->where('name', 'like', "%{$term}%"))
            ))
            ->orderBy('updated_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.verifications.index', [
            'students' => $students,
            'verifiedThisMonth' => Student::where('verification_status', VerificationStatus::Verified)
                ->whereMonth('verified_at', now()->month)->whereYear('verified_at', now()->year)->count(),
        ]);
    }

    public function show(Student $student): View
    {
        $student->load(['user', 'studyProgram', 'skills', 'experiences', 'educations', 'certificates', 'portfolios']);

        return view('admin.verifications.show', ['student' => $student]);
    }

    public function approve(Student $student, StudentVerificationService $verification, Request $request): RedirectResponse
    {
        $verification->approve($student, $request->user());

        return redirect()->route('admin.verifications.index')->with('status', 'Profil mahasiswa disetujui.');
    }

    public function reject(RejectStudentRequest $request, Student $student, StudentVerificationService $verification): RedirectResponse
    {
        $verification->reject($student, $request->user(), $request->reason);

        return redirect()->route('admin.verifications.index')->with('status', 'Profil mahasiswa ditolak.');
    }
}
