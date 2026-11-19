<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class QuickAddShortlistItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shortlist_id' => ['required', 'exists:shortlists,id'],
            'student_id' => ['required', 'exists:students,id'],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
