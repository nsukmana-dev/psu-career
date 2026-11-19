<?php

namespace App\Services;

use App\Enums\VerificationStatus;
use App\Models\Student;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class CandidateSearchService
{
    /**
     * The only place candidate search queries are built. Never add email/phone/
     * birth_date/linkedin_url to the select or eager loads here (business rule 1).
     */
    public function search(array $filters, int $perPage = 20): LengthAwarePaginator
    {
        $skillIds = array_filter((array) ($filters['skill_ids'] ?? []));

        $query = Student::query()
            ->where('verification_status', VerificationStatus::Verified)
            ->where('open_to_contact', true)
            ->whereHas('user', fn (Builder $q) => $q->where('is_active', true))
            ->with(['studyProgram', 'skills']);

        if ($skillIds !== []) {
            $query->withCount(['skills as matched_skills_count' => fn (Builder $q) => $q->whereIn('skills.id', $skillIds)]);
        }

        $this->applyFilters($query, $filters, $skillIds);
        $this->applySort($query, $filters['sort'] ?? 'relevance', $skillIds);

        return $query->paginate($perPage)->withQueryString();
    }

    private function applyFilters(Builder $query, array $filters, array $skillIds): void
    {
        if (! empty($filters['study_program_ids'])) {
            $query->whereIn('study_program_id', (array) $filters['study_program_ids']);
        }

        if (! empty($filters['min_semester'])) {
            $query->where('current_semester', '>=', (int) $filters['min_semester']);
        }

        if (! empty($filters['entry_year'])) {
            $query->where('entry_year', (int) $filters['entry_year']);
        }

        if (! empty($filters['min_gpa'])) {
            $query->where('gpa', '>=', (float) $filters['min_gpa']);
        }

        if ($skillIds !== []) {
            if (($filters['skill_mode'] ?? 'any') === 'all') {
                foreach ($skillIds as $skillId) {
                    $query->whereHas('skills', fn (Builder $q) => $q->where('skills.id', $skillId));
                }
            } else {
                $query->whereHas('skills', fn (Builder $q) => $q->whereIn('skills.id', $skillIds));
            }
        }

        if (! empty($filters['city'])) {
            $query->where('city', 'like', '%'.$filters['city'].'%');
        }

        if (! empty($filters['job_interest'])) {
            $query->whereIn('job_interest', [$filters['job_interest'], 'both']);
        }

        if (! empty($filters['willing_to_relocate'])) {
            $query->where('willing_to_relocate', true);
        }

        if (! empty($filters['keyword'])) {
            $keyword = $filters['keyword'];
            $query->where(function (Builder $q) use ($keyword) {
                $q->whereHas('user', fn (Builder $u) => $u->where('name', 'like', "%{$keyword}%"))
                    ->orWhere('summary', 'like', "%{$keyword}%")
                    ->orWhereHas('portfolios', fn (Builder $p) => $p->where('title', 'like', "%{$keyword}%"));
            });
        }
    }

    private function applySort(Builder $query, string $sort, array $skillIds): void
    {
        match ($sort) {
            'gpa_desc' => $query->orderByDesc('gpa'),
            'semester_desc' => $query->orderByDesc('current_semester'),
            'updated_desc' => $query->orderByDesc('updated_at'),
            default => $skillIds !== []
                ? $query->orderByDesc('matched_skills_count')
                : $query->orderByDesc('updated_at'),
        };
    }
}
