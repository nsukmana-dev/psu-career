<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreIndustryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $industryId = $this->route('industry')?->id;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('industries', 'name')->ignore($industryId)],
        ];
    }
}
