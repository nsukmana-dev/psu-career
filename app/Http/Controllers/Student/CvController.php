<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\UpdateCvSettingsRequest;
use App\Models\CvTemplate;
use App\Services\CvDataBuilder;
use App\Services\CvPdfService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class CvController extends Controller
{
    public function index(Request $request): View
    {
        return view('student.cv.index', [
            'student' => $request->user()->student,
            'templates' => CvTemplate::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function preview(Request $request, CvTemplate $template, CvDataBuilder $builder): View
    {
        return view($template->blade_view, $builder->build($request->user()->student));
    }

    public function download(Request $request, CvTemplate $template, CvPdfService $pdf): Response
    {
        return $pdf->download($request->user()->student, $template);
    }

    public function updateSettings(UpdateCvSettingsRequest $request): RedirectResponse
    {
        $request->user()->student->update($request->validated());

        return back()->with('status', 'Pengaturan CV tersimpan.');
    }
}
