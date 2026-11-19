<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreExperienceRequest;
use App\Models\Experience;
use App\Services\ProfileCompletionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function store(StoreExperienceRequest $request, ProfileCompletionService $completion): RedirectResponse
    {
        $student = $request->user()->student;
        $student->experiences()->create($request->validated());
        $completion->refresh($student);

        return back()->with('status', 'Pengalaman ditambahkan.');
    }

    public function destroy(Request $request, Experience $experience, ProfileCompletionService $completion): RedirectResponse
    {
        abort_unless($experience->student_id === $request->user()->student->id, 403);

        $student = $experience->student;
        $experience->delete();
        $completion->refresh($student);

        return back()->with('status', 'Pengalaman dihapus.');
    }
}
