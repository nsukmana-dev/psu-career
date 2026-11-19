<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreSkillRequest;
use App\Models\Skill;
use App\Services\ProfileCompletionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function store(StoreSkillRequest $request, ProfileCompletionService $completion): RedirectResponse
    {
        $student = $request->user()->student;

        $skill = Skill::firstOrCreate(
            ['name' => $request->skill_name],
            ['is_approved' => false, 'suggested_by' => $request->user()->id]
        );

        $student->skills()->syncWithoutDetaching([$skill->id => ['level' => $request->level]]);
        $completion->refresh($student);

        return back()->with('status', $skill->wasRecentlyCreated
            ? 'Skill ditambahkan dan diusulkan ke admin untuk disetujui.'
            : 'Skill ditambahkan.');
    }

    public function destroy(Request $request, Skill $skill, ProfileCompletionService $completion): RedirectResponse
    {
        $student = $request->user()->student;
        $student->skills()->detach($skill->id);
        $completion->refresh($student);

        return back()->with('status', 'Skill dihapus.');
    }
}
