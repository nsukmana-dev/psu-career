<?php

namespace App\Http\Controllers\Company;

use App\Enums\VerificationStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\CandidateResource;
use App\Models\CvTemplate;
use App\Models\ProfileView;
use App\Models\Skill;
use App\Models\Student;
use App\Models\StudyProgram;
use App\Services\CandidateSearchService;
use App\Services\CvDataBuilder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CandidateController extends Controller
{
    public function index(Request $request, CandidateSearchService $search): View
    {
        $candidates = $search->search($request->query());
        $company = $request->user()->company;

        return view('company.candidates.index', [
            'candidates' => $candidates->through(fn (Student $student) => new CandidateResource($student, $company)),
            'studyPrograms' => StudyProgram::where('is_active', true)->orderBy('name')->get(),
            'skills' => Skill::where('is_approved', true)->orderBy('name')->get(),
            'savedSearches' => $request->user()->company->savedSearches()->orderByDesc('created_at')->get(),
        ]);
    }

    public function show(Request $request, Student $student, CvDataBuilder $builder): View
    {
        abort_unless(
            $student->verification_status === VerificationStatus::Verified && $student->open_to_contact,
            404
        );

        $company = $request->user()->company;

        if (ProfileView::where('company_id', $company->id)->where('student_id', $student->id)
            ->whereDate('viewed_at', today())->doesntExist()) {
            ProfileView::create(['company_id' => $company->id, 'student_id' => $student->id, 'viewed_at' => now()]);
        }

        $student->load(['studyProgram', 'skills', 'experiences', 'educations', 'certificates', 'portfolios']);
        $template = $student->defaultCvTemplate ?? CvTemplate::where('is_active', true)->orderBy('sort_order')->first();

        return view('company.candidates.show', [
            'student' => $student,
            'candidate' => new CandidateResource($student, $company),
            'shortlists' => $company->shortlists,
        ]);
    }

    public function cvPreview(Student $student, CvDataBuilder $builder): View
    {
        abort_unless(
            $student->verification_status === VerificationStatus::Verified && $student->open_to_contact,
            404
        );

        $template = $student->defaultCvTemplate ?? CvTemplate::where('is_active', true)->orderBy('sort_order')->first();

        return view($template->blade_view, $builder->build($student, includeContact: false));
    }
}
