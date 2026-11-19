<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\UpdateBasicProfileRequest;
use App\Models\StudyProgram;
use App\Services\ImageUploadService;
use App\Services\ProfileCompletionService;
use App\Services\StudentUpdateRequestService;
use App\Services\StudentVerificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $student = $request->user()->student->load([
            'studyProgram', 'skills', 'experiences', 'educations', 'certificates', 'portfolios', 'updateRequests',
        ]);

        return view('student.profile.edit', [
            'student' => $student,
            'studyPrograms' => StudyProgram::where('is_active', true)->orderBy('name')->get(),
            'pendingUpdateRequest' => $student->updateRequests->firstWhere('status', 'pending'),
        ]);
    }

    public function updateBasic(
        UpdateBasicProfileRequest $request,
        StudentUpdateRequestService $updateRequests,
        ProfileCompletionService $completion,
        ImageUploadService $images
    ): RedirectResponse {
        $student = $request->user()->student;
        $this->authorize('update', $student);

        $data = $request->safe()->except(['name', 'photo']);
        [$direct, $diff] = $updateRequests->splitChanges($student, $data);

        DB::transaction(function () use ($request, $student, $direct, $diff, $updateRequests, $images) {
            $request->user()->update(['name' => $request->name]);

            if ($request->hasFile('photo')) {
                if ($student->photo_path) {
                    Storage::disk('public')->delete($student->photo_path);
                }

                $direct['photo_path'] = $images->storeResized(
                    $request->file('photo'), 'public', 'students/'.$student->id, 400, 400
                );
            }

            if ($direct !== []) {
                $student->update($direct);
            }

            if ($diff !== []) {
                $updateRequests->submit($student, $diff);
            }
        });

        $completion->refresh($student);

        return back()->with('status', $diff !== []
            ? 'Perubahan data diri tersimpan. Perubahan data akademik menunggu persetujuan admin.'
            : 'Perubahan data diri tersimpan.');
    }

    public function submit(Request $request, StudentVerificationService $verification): RedirectResponse
    {
        $student = $request->user()->student;
        $this->authorize('update', $student);

        $verification->submit($student);

        return back()->with('status', 'Profil diajukan untuk verifikasi admin.');
    }
}
