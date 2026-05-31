<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobVacancyCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Set to true to allow all authenticated users to proceed with the request
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Validation rules based on JobVacancy Model's $fillable fields
            'title'          => 'required|string|max:255',
            'description'    => 'required|string',
            'location'       => 'required|string|max:255',
            // Example: Restricting 'type' to specific allowed values
            'type'           => 'required|string|in:Full-Time,Part-Time,Remote,Contract',
            'salary'         => 'nullable|numeric|min:0',
            // Must be a string (UUID) and must exist in the 'companies' table
            'company_id'     => 'required|string|exists:companies,id',
            // Must be a string (UUID) and must exist in the 'job_categories' table
            'jobcategory_id' => 'required|string|exists:job_categories,id',
        ];
    }

    /**
     * Customize validation messages (optional but recommended).
     */
    public function messages(): array
    {
        return [
            // Custom messages for better user feedback
            'title.required'          => 'The job title is required.',
            'description.required'    => 'The job description is required.',
            'location.required'       => 'The job location is required.',
            'type.in'                 => 'Please select a valid job type (Full-Time, Part-Time, etc.).',
            'company_id.exists'       => 'The selected company is invalid.',
            'jobcategory_id.exists'   => 'The selected job category is invalid.',
        ];
    }
}
