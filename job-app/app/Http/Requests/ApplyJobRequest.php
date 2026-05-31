<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyJobRequest extends FormRequest
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
        return [
            'resume_option' => 'required|string',
            'resume_file' => 'required_if:resume_option,new_resume|file|mimes:pdf|max:5120',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'resume_option.required' => 'Please select a resume option.',
            'resume_file.required_if' => 'Please select a resume file to upload.',
            'resume_file.file' => 'The resume file must be a file.',
            'resume_file.mimes' => 'The resume file must be a PDF file.',
            'resume_file.max' => 'The resume file may not be greater than 5MB.',
        ];
    }
}
