<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobVacancyUpdateRequest extends FormRequest
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
            'title'          => 'required|string|max:255',
            'description'    => 'required|string',
            'location'       => 'required|string|max:255',
            'type'           => 'required|string|in:Full-Time,Part-Time,Remote,Contract',
            'salary'         => 'nullable|numeric|min:0',
            'company_id'     => 'required|string|exists:companies,id',
            'jobcategory_id' => 'required|string|exists:job_categories,id',
        ];
    }

    /**
     * Customize validation messages (optional).
     */
    public function messages(): array
    {
        return [
            'title.required'          => 'The job title is required for update.',
            'description.required'    => 'The job description is required for update.',
            // ... (Other custom messages can be added here)
        ];
    }
}
