<?php

namespace App\Models;

use App\Enums\StudyProgramLevel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['code', 'name', 'level', 'is_active'])]
class StudyProgram extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'level' => StudyProgramLevel::class,
            'is_active' => 'boolean',
        ];
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
