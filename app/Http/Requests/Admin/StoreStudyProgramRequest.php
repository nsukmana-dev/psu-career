<?php

namespace App\Http\Requests\Admin;

use App\Enums\StudyProgramLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStudyProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $programId = $this->route('studyProgram')?->id;

        return [
            'code' => ['required', 'string', 'max:20', Rule::unique('study_programs', 'code')->ignore($programId)],
            'name' => ['required', 'string', 'max:255'],
            'level' => ['required', Rule::in(array_column(StudyProgramLevel::cases(), 'value'))],
        ];
    }
}
