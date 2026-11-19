<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['shortlist_id', 'student_id', 'note'])]
class ShortlistItem extends Model
{
    public function shortlist(): BelongsTo
    {
        return $this->belongsTo(Shortlist::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
