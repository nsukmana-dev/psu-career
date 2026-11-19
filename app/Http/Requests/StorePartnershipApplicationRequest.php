<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePartnershipApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255'],
            'industry_id' => ['nullable', 'exists:industries,id'],
            'pic_name' => ['required', 'string', 'max:255'],
            'pic_position' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'needs_internship' => ['boolean'],
            'needs_fulltime' => ['boolean'],
            'message' => ['nullable', 'string', 'max:1000'],
            // Honeypot: hidden field a real visitor never fills; bots filling every field trip this.
            'website' => ['prohibited'],
        ];
    }
}
