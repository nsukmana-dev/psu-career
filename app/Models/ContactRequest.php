<?php

namespace App\Models;

use App\Enums\EmploymentType;
use App\Enums\RequestStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['request_number', 'company_id', 'position_title', 'employment_type', 'work_location', 'message', 'status'])]
class ContactRequest extends Model
{
    protected function casts(): array
    {
        return [
            'employment_type' => EmploymentType::class,
            'status' => RequestStatus::class,
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ContactRequestItem::class);
    }
}
