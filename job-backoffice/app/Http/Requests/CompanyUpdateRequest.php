<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyUpdateRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:companies,name,' . $this->route('company'),
            // Add other fields and their validation rules as necessary
            'address' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'website' => 'nullable|url|max:255',
            'owner_name' => 'required|string|max:255',
            'owner_password' => 'nullable|string|min:8|max:255',
        ];
    }


    public function messages(): array
    {
        return [
            'name.required' => 'The category name is required.',
            'name.unique' => 'The category name has already been taken.',
            'name.max' => 'The category name may not be greater than 255 characters.',
            'name.string' => 'The category name must be a string.',
            'address.required' => 'The adress is required.',
            'address.max' => 'The adress may not be greater than 255 characters.',
            'address.string' => 'The adress must be a string.',
            'industry.required' => 'The industry is required.',
            'industry.max' => 'The industry may not be greater than 255 characters.',
            'industry.string' => 'The industry must be a string.',
            'website.url' => 'The website must be a valid URL.',
            'website.max' => 'The website may not be greater than 255 characters.',
            'owner_name.required' => 'The Owner name is required.',
            'owner_name.string' => 'The Owner name must be a string.',
            'owner_name.max' => 'The company name may not be greater than 255 characters.',
            'owner_password.min' => 'The password name may not be greater than 255 characters.',
            'owner_password.max' => 'The password name may not be greater than 255 characters.',

        ];
    }
}
