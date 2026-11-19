<?php

namespace App\Http\Requests\Student;

use App\Enums\JobInterest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePreferencesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'job_interest' => ['required', Rule::in(array_column(JobInterest::cases(), 'value'))],
            'willing_to_relocate' => ['boolean'],
            'open_to_contact' => ['boolean'],
        ];
    }
}
