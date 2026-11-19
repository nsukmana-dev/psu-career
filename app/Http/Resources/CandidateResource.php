<?php

namespace App\Http\Resources;

use App\Enums\ItemStatus;
use App\Enums\SkillLevel;
use App\Models\Company;
use App\Models\ContactRequestItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * The single choke point for what a company is allowed to see about a candidate.
 * Deliberately never references email/phone/birth_date/linkedin_url (business rule 1) —
 * those live only on Student/User and are never touched here.
 */
class CandidateResource extends JsonResource
{
    public function __construct($resource, private ?Company $company = null)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'short_name' => $this->shortName(),
            'study_program' => $this->studyProgram->name,
            'semester' => $this->current_semester,
            'gpa' => $this->gpa,
            'city' => $this->city,
            'skills' => $this->skills->take(4)->map(fn ($skill) => [
                'name' => $skill->name,
                'level' => SkillLevel::from($skill->pivot->level)->label(),
            ]),
            'contact_status' => $this->contactStatus(),
        ];
    }

    private function contactStatus(): string
    {
        if (! $this->company) {
            return 'hidden';
        }

        $status = ContactRequestItem::where('student_id', $this->id)
            ->whereHas('contactRequest', fn ($q) => $q->where('company_id', $this->company->id))
            ->latest('id')
            ->value('status');

        return match ($status) {
            ItemStatus::Approved => 'shared',
            ItemStatus::Pending => 'pending',
            default => 'hidden',
        };
    }

    private function shortName(): string
    {
        $parts = array_filter(explode(' ', $this->user->name));
        $first = array_shift($parts) ?? '';
        $lastInitial = $parts !== [] ? mb_substr(array_shift($parts), 0, 1).'.' : '';

        return trim($first.' '.$lastInitial);
    }
}
