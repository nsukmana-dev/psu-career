<?php

namespace App\Models;

use App\Enums\ApprovalStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'company_name', 'industry_id', 'pic_name', 'pic_position', 'email', 'phone',
    'needs_internship', 'needs_fulltime', 'message', 'status',
    'reviewed_by', 'reviewed_at', 'rejection_reason', 'company_id',
])]
class PartnershipApplication extends Model
{
    protected function casts(): array
    {
        return [
            'needs_internship' => 'boolean',
            'needs_fulltime' => 'boolean',
            'status' => ApprovalStatus::class,
            'reviewed_at' => 'datetime',
        ];
    }

    public function industry(): BelongsTo
    {
        return $this->belongsTo(Industry::class);
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
