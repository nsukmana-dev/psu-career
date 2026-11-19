<?php

namespace App\Http\Requests\Admin;

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
        $skillId = $this->route('skill')?->id;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('skills', 'name')->ignore($skillId)],
            'category' => ['nullable', 'string', 'max:255'],
        ];
    }
}
