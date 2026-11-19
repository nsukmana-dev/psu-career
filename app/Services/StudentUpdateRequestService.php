<?php

namespace App\Services;

use App\Enums\ApprovalStatus;
use App\Enums\VerificationStatus;
use App\Models\ActivityLog;
use App\Models\Student;
use App\Models\StudentUpdateRequest;
use App\Models\User;

class StudentUpdateRequestService
{
    /**
     * Academic fields that require admin approval once a student is verified.
     */
    public const SENSITIVE_FIELDS = ['current_semester', 'gpa', 'study_program_id', 'entry_year'];

    public function __construct(private ProfileCompletionService $completion) {}

    /**
     * Split incoming attributes into [direct-save fields, pending-approval diff].
     * Sensitive fields only require approval once the student is verified —
     * before that, students can still freely correct their own draft data.
     */
    public function splitChanges(Student $student, array $incoming): array
    {
        $direct = [];
        $diff = [];

        foreach ($incoming as $field => $value) {
            $isSensitive = in_array($field, self::SENSITIVE_FIELDS, true);
            // Loose comparison: Student's decimal cast formats gpa as "3.50", form input may send "3.5".
            $changed = $student->{$field} != $value;

            if ($isSensitive && $student->verification_status === VerificationStatus::Verified) {
                if ($changed) {
                    $diff[$field] = ['old' => $student->{$field}, 'new' => $value];
                }
            } else {
                $direct[$field] = $value;
            }
        }

        return [$direct, $diff];
    }

    public function submit(Student $student, array $changes): StudentUpdateRequest
    {
        return $student->updateRequests()->create([
            'changes' => $changes,
            'status' => ApprovalStatus::Pending,
        ]);
    }

    public function approve(StudentUpdateRequest $request, User $admin, ?string $note = null): void
    {
        $values = collect($request->changes)->map(fn ($change) => $change['new'])->all();

        $request->student->update($values);
        $this->completion->refresh($request->student);

        $request->update([
            'status' => ApprovalStatus::Approved,
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
            'note' => $note,
        ]);

        ActivityLog::record('update_request.approved', $request);
    }

    public function reject(StudentUpdateRequest $request, User $admin, string $note): void
    {
        $request->update([
            'status' => ApprovalStatus::Rejected,
            'reviewed_by' => $admin->id,
            'reviewed_at' => now(),
            'note' => $note,
        ]);

        ActivityLog::record('update_request.rejected', $request);
    }
}
