<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobApplicationUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Update rules are usually the same as create rules for this model,
        // as all fields are mandatory for an existing vacancy.
        return [
        'status' => 'required|string|in:pending,reviewed,rejected,accepted',
        ];
    }

    /**
     * Customize validation messages (optional).
     */
    public function messages(): array
    {
        return [
            'status.required' => 'The job status is required for update.',
            'status.in'  => 'The job status must be one of: pending, reviewed, rejected, accepted.',
        ];

        
    }
}
