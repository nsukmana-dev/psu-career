<?php

namespace App\Services;

use App\Enums\VerificationStatus;
use App\Models\ActivityLog;
use App\Models\Student;
use App\Models\User;

class StudentVerificationService
{
    public function submit(Student $student): void
    {
        $student->update(['verification_status' => VerificationStatus::Pending]);
    }

    public function approve(Student $student, User $admin): void
    {
        $student->update([
            'verification_status' => VerificationStatus::Verified,
            'verified_at' => now(),
            'verified_by' => $admin->id,
            'rejection_reason' => null,
        ]);

        ActivityLog::record('student.verified', $student);
    }

    public function reject(Student $student, User $admin, string $reason): void
    {
        $student->update([
            'verification_status' => VerificationStatus::Rejected,
            'verified_at' => null,
            'verified_by' => $admin->id,
            'rejection_reason' => $reason,
        ]);

        ActivityLog::record('student.rejected', $student, ['reason' => $reason]);
    }
}
