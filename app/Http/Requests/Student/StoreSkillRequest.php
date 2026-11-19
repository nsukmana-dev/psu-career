<?php

namespace App\Http\Requests\Student;

use App\Enums\SkillLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSkillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'skill_name' => ['required', 'string', 'max:255'],
            'level' => ['required', Rule::in(array_column(SkillLevel::cases(), 'value'))],
        ];
    }
}
