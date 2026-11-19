<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateCvTemplateRequest;
use App\Models\CvTemplate;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CvTemplateController extends Controller
{
    public function index(): View
    {
        return view('admin.cv-templates.index', [
            'templates' => CvTemplate::orderBy('sort_order')->get(),
        ]);
    }

    public function update(UpdateCvTemplateRequest $request, CvTemplate $cvTemplate, ImageUploadService $images): RedirectResponse
    {
        $data = $request->safe()->except('preview_image');

        if ($request->hasFile('preview_image')) {
            $data['preview_image'] = $images->storeResized($request->file('preview_image'), 'public', 'cv-templates', 400, 560);
        }

        $cvTemplate->update($data);

        return back()->with('status', 'Template CV diperbarui.');
    }

    public function toggleActive(CvTemplate $cvTemplate): RedirectResponse
    {
        $cvTemplate->update(['is_active' => ! $cvTemplate->is_active]);

        return back()->with('status', $cvTemplate->is_active ? 'Template diaktifkan.' : 'Template dinonaktifkan.');
    }
}
