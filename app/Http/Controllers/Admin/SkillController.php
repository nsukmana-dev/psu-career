<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSkillRequest;
use App\Models\Skill;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SkillController extends Controller
{
    public function index(): View
    {
        return view('admin.skills.index', [
            'skills' => Skill::orderByDesc('is_approved')->orderBy('name')->get(),
        ]);
    }

    public function store(StoreSkillRequest $request): RedirectResponse
    {
        Skill::create([...$request->validated(), 'is_approved' => true]);

        return back()->with('status', 'Skill ditambahkan.');
    }

    public function update(StoreSkillRequest $request, Skill $skill): RedirectResponse
    {
        $skill->update($request->validated());

        return back()->with('status', 'Skill diperbarui.');
    }

    public function approve(Skill $skill): RedirectResponse
    {
        $skill->update(['is_approved' => true]);

        return back()->with('status', 'Skill disetujui.');
    }

    public function destroy(Skill $skill): RedirectResponse
    {
        $skill->delete();

        return back()->with('status', 'Skill dihapus.');
    }
}
