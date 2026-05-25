<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTravelerRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'family_member_id' => ['nullable', 'exists:family_members,id'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'dob' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:30'],
            'passport_no' => ['nullable', 'string', 'max:80'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'visa_type' => ['nullable', 'string', 'max:80'],
            'emergency_contact' => ['nullable', 'string', 'max:80'],
        ];
    }
}
