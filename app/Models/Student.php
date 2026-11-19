<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\JobInterest;
use App\Enums\VerificationStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'user_id', 'nim', 'study_program_id', 'entry_year', 'current_semester', 'gpa',
    'gender', 'birth_date', 'city', 'phone', 'linkedin_url', 'portfolio_url', 'summary', 'photo_path',
    'job_interest', 'willing_to_relocate', 'open_to_contact',
    'verification_status', 'verified_at', 'verified_by', 'rejection_reason',
    'default_cv_template_id', 'cv_accent_color', 'profile_completion',
])]
class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'gender' => Gender::class,
            'birth_date' => 'date',
            'job_interest' => JobInterest::class,
            'willing_to_relocate' => 'boolean',
            'open_to_contact' => 'boolean',
            'verification_status' => VerificationStatus::class,
            'verified_at' => 'datetime',
            'gpa' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function defaultCvTemplate(): BelongsTo
    {
        return $this->belongsTo(CvTemplate::class, 'default_cv_template_id');
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'student_skill')
            ->withPivot('level')
            ->withTimestamps();
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(Experience::class);
    }

    public function educations(): HasMany
    {
        return $this->hasMany(Education::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function portfolios(): HasMany
    {
        return $this->hasMany(Portfolio::class);
    }

    public function updateRequests(): HasMany
    {
        return $this->hasMany(StudentUpdateRequest::class);
    }

    public function shortlistItems(): HasMany
    {
        return $this->hasMany(ShortlistItem::class);
    }

    public function contactRequestItems(): HasMany
    {
        return $this->hasMany(ContactRequestItem::class);
    }

    public function profileViews(): HasMany
    {
        return $this->hasMany(ProfileView::class);
    }

    public function isVerified(): bool
    {
        return $this->verification_status === VerificationStatus::Verified;
    }

    public function initials(): string
    {
        $name = $this->user?->name ?? '';
        $parts = array_filter(explode(' ', $name));

        return mb_strtoupper(mb_substr($parts[0] ?? '', 0, 1).mb_substr($parts[1] ?? '', 0, 1));
    }
}
