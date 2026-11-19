<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class StoreEducationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'institution' => ['required', 'string', 'max:255'],
            'major' => ['required', 'string', 'max:255'],
            'level' => ['required', 'string', 'max:100'],
            'start_year' => ['required', 'integer', 'between:1980,'.now()->year],
            'end_year' => ['nullable', 'integer', 'gte:start_year'],
            'score' => ['nullable', 'string', 'max:50'],
        ];
    }
}
