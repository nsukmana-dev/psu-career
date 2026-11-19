<?php

namespace App\Http\Requests\Student;

use App\Enums\Gender;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBasicProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->user()->student);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'study_program_id' => ['required', 'exists:study_programs,id'],
            'entry_year' => ['required', 'integer', 'between:2000,'.now()->year],
            'current_semester' => ['required', 'integer', 'between:1,14'],
            'gpa' => ['required', 'numeric', 'between:0,4'],
            'gender' => ['required', Rule::in(array_column(Gender::cases(), 'value'))],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'city' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'summary' => ['nullable', 'string', 'max:600'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ];
    }
}
