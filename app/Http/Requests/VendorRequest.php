<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'business_name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:vendors,slug,' . $this->route('vendor')?->id],
            'category_id' => ['nullable', 'exists:service_categories,id'],
            'description' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'state' => ['nullable', 'string'],
            'country' => ['nullable', 'string'],
            'postal_code' => ['nullable', 'string'],
            'phone' => ['nullable', 'string'],
            'website' => ['nullable', 'url'],
            'social_links' => ['nullable', 'array'],
            'status' => ['nullable', 'in:pending,active,suspended,verified'],
        ];
    }
}
