<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vendor_id' => ['required', 'exists:vendors,id'],
            'customer_id' => ['required', 'exists:users,id'],
            'staff_id' => ['nullable', 'exists:staff,id'],
            'service_id' => ['required', 'exists:services,id'],
            'status' => ['nullable', 'in:pending,confirmed,in_progress,completed,cancelled,no_show'],
            'payment_status' => ['nullable', 'in:pending,paid,failed,refunded'],
            'scheduled_at' => ['required', 'date'],
            'ends_at' => ['required', 'date', 'after:scheduled_at'],
            'duration_minutes' => ['required', 'integer', 'min:1'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'total' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
