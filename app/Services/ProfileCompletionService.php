<?php

namespace App\Services;

use App\Models\Student;

class ProfileCompletionService
{
    public function calculate(Student $student): int
    {
        $student->loadMissing(['skills', 'experiences', 'educations', 'certificates', 'portfolios']);

        $score = 0;

        if ($student->city && $student->phone && $student->birth_date && $student->gender && $student->photo_path && $student->summary) {
            $score += 30;
        }

        if ($student->skills->isNotEmpty()) {
            $score += 15;
        }

        if ($student->experiences->isNotEmpty() || $student->educations->isNotEmpty()) {
            $score += 15;
        }

        if ($student->portfolios->isNotEmpty()) {
            $score += 10;
        }

        if ($student->certificates->isNotEmpty()) {
            $score += 10;
        }

        if ($student->job_interest !== null) {
            $score += 10;
        }

        if ($student->default_cv_template_id !== null) {
            $score += 10;
        }

        return $score;
    }

    public function refresh(Student $student): void
    {
        $student->update(['profile_completion' => $this->calculate($student)]);
    }
}
