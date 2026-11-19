<?php

namespace App\Http\Requests\Admin;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:'.User::class],
            'nim' => ['required', 'string', 'max:20', 'unique:students,nim'],
            'study_program_id' => ['required', 'exists:study_programs,id'],
            'entry_year' => ['nullable', 'integer', 'between:2000,'.now()->year],
        ];
    }
}
