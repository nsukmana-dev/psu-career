<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStudyProgramRequest;
use App\Models\StudyProgram;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StudyProgramController extends Controller
{
    public function index(): View
    {
        return view('admin.study-programs.index', [
            'studyPrograms' => StudyProgram::withCount('students')->orderBy('name')->get(),
        ]);
    }

    public function store(StoreStudyProgramRequest $request): RedirectResponse
    {
        StudyProgram::create([...$request->validated(), 'is_active' => true]);

        return back()->with('status', 'Program studi ditambahkan.');
    }

    public function update(StoreStudyProgramRequest $request, StudyProgram $studyProgram): RedirectResponse
    {
        $studyProgram->update($request->validated());

        return back()->with('status', 'Program studi diperbarui.');
    }

    public function toggleActive(StudyProgram $studyProgram): RedirectResponse
    {
        $studyProgram->update(['is_active' => ! $studyProgram->is_active]);

        return back()->with('status', $studyProgram->is_active ? 'Program studi diaktifkan.' : 'Program studi dinonaktifkan.');
    }
}
