<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['student_id', 'institution', 'major', 'level', 'start_year', 'end_year', 'score'])]
class Education extends Model
{
    protected $table = 'educations';

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
