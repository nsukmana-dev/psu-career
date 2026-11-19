<?php

namespace App\Services;

use App\Enums\SkillLevel;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;

class CvDataBuilder
{
    /**
     * Build the data array fed to every CV Blade template.
     *
     * $includeContact must stay false for anything a company can see (business rule 1) —
     * only the student's own preview/download and admin views may pass true.
     */
    public function build(Student $student, bool $includeContact = true): array
    {
        $student->loadMissing([
            'user', 'studyProgram', 'skills', 'experiences', 'educations', 'certificates', 'portfolios',
        ]);

        $data = [
            'name' => $student->user->name,
            // Base64 data URI so the exact same markup renders identically in the browser
            // preview and inside DomPDF, with no remote HTTP image fetch either way.
            'photoDataUri' => $this->photoDataUri($student->photo_path),
            'summary' => $student->summary,
            'studyProgram' => $student->studyProgram->name,
            'entryYear' => $student->entry_year,
            'semester' => $student->current_semester,
            'gpa' => $student->gpa,
            'city' => $student->city,
            'accentColor' => $student->cv_accent_color ?? '#0F2A4A',
            'skills' => $student->skills
                ->map(fn ($skill) => ['name' => $skill->name, 'level' => SkillLevel::from($skill->pivot->level)->label()])
                ->all(),
            'experiences' => $student->experiences->sortByDesc('start_date')->values(),
            'educations' => $student->educations->sortByDesc('start_year')->values(),
            'portfolios' => $student->portfolios,
        ];

        if ($includeContact) {
            $data['email'] = $student->user->email;
            $data['phone'] = $student->phone;
            $data['linkedinUrl'] = $student->linkedin_url;
            $data['portfolioUrl'] = $student->portfolio_url;
        }

        return $data;
    }

    private function photoDataUri(?string $path): ?string
    {
        if (! $path || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        return 'data:image/jpeg;base64,'.base64_encode(Storage::disk('public')->get($path));
    }
}
