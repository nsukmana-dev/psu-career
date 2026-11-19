<?php

namespace App\Http\Controllers;

use App\Enums\CompanyStatus;
use App\Enums\FaqAudience;
use App\Enums\ItemStatus;
use App\Enums\VerificationStatus;
use App\Models\Company;
use App\Models\ContactRequestItem;
use App\Models\CvTemplate;
use App\Models\Faq;
use App\Models\Industry;
use App\Models\LandingSetting;
use App\Models\Skill;
use App\Models\Student;
use App\Models\StudyProgram;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function home(): View
    {
        return view('landing.home', [
            'settings' => $this->settings(),
            'stats' => $this->stats(),
            'studyPrograms' => StudyProgram::where('is_active', true)->orderBy('name')->get(),
        ]);
    }

    public function students(): View
    {
        return view('landing.students', [
            'settings' => $this->settings(),
            'stats' => $this->stats(),
            'studyPrograms' => StudyProgram::where('is_active', true)->orderBy('name')->get(),
            'cvTemplates' => CvTemplate::where('is_active', true)->orderBy('sort_order')->get(),
            'faqs' => Faq::where('audience', FaqAudience::Student)->where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }

    public function companies(): View
    {
        return view('landing.companies', [
            'settings' => $this->settings(),
            'stats' => $this->stats(),
            'talentPool' => $this->talentPool(),
            'faqs' => Faq::where('audience', FaqAudience::Company)->where('is_active', true)->orderBy('sort_order')->get(),
            'industries' => Industry::orderBy('name')->get(),
        ]);
    }

    private function settings(): \Illuminate\Support\Collection
    {
        return LandingSetting::pluck('value', 'key');
    }

    private function stats(): array
    {
        return [
            'students' => Student::where('verification_status', VerificationStatus::Verified)->count(),
            'companies' => Company::where('status', CompanyStatus::Active)->count(),
            'studyPrograms' => StudyProgram::where('is_active', true)->count(),
            'connected' => ContactRequestItem::where('status', ItemStatus::Approved)->distinct('student_id')->count('student_id'),
        ];
    }

    private function talentPool(): \Illuminate\Support\Collection
    {
        // ponytail: was Cache::remember()'d, but the handful of queries here (one per active
        // study program) are cheap enough to run live — not worth a cache layer at this traffic scale.
        return StudyProgram::where('is_active', true)->get()->map(function (StudyProgram $program) {
            $activeCount = Student::where('study_program_id', $program->id)
                ->where('verification_status', VerificationStatus::Verified)
                ->where('open_to_contact', true)
                ->count();

            $topSkills = Skill::query()
                ->join('student_skill', 'skills.id', '=', 'student_skill.skill_id')
                ->join('students', 'student_skill.student_id', '=', 'students.id')
                ->where('students.study_program_id', $program->id)
                ->where('students.verification_status', VerificationStatus::Verified->value)
                ->where('students.open_to_contact', true)
                ->select('skills.name')
                ->selectRaw('count(*) as total')
                ->groupBy('skills.id', 'skills.name')
                ->orderByDesc('total')
                ->limit(3)
                ->pluck('skills.name');

            return ['program' => $program, 'activeCount' => $activeCount, 'topSkills' => $topSkills];
        });
    }
}
