<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreEducationRequest;
use App\Models\Education;
use App\Services\ProfileCompletionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    public function store(StoreEducationRequest $request, ProfileCompletionService $completion): RedirectResponse
    {
        $student = $request->user()->student;
        $student->educations()->create($request->validated());
        $completion->refresh($student);

        return back()->with('status', 'Riwayat pendidikan ditambahkan.');
    }

    public function destroy(Request $request, Education $education, ProfileCompletionService $completion): RedirectResponse
    {
        abort_unless($education->student_id === $request->user()->student->id, 403);

        $student = $education->student;
        $education->delete();
        $completion->refresh($student);

        return back()->with('status', 'Riwayat pendidikan dihapus.');
    }
}
