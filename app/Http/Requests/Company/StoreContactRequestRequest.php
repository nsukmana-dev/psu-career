<?php

namespace App\Http\Requests\Company;

use App\Enums\EmploymentType;
use App\Services\ContactRequestService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContactRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'position_title' => ['required', 'string', 'max:255'],
            'employment_type' => ['required', Rule::in(array_column(EmploymentType::cases(), 'value'))],
            'work_location' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:1000'],
            'student_ids' => ['required', 'array', 'min:1', 'max:'.ContactRequestService::MAX_STUDENTS_PER_REQUEST],
            'student_ids.*' => ['exists:students,id'],
        ];
    }
}
