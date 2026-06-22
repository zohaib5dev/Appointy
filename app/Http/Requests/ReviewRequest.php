<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'appointment_id' => ['required', 'exists:appointments,id'],
            'vendor_id' => ['required', 'exists:vendors,id'],
            'customer_id' => ['required', 'exists:users,id'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'review' => ['nullable', 'string'],
            'status' => ['nullable', 'in:published,pending,rejected'],
        ];
    }
}
