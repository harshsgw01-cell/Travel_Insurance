<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
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
        $customer = $this->route('customer');

        return [
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', 'max:255', 'unique:customers,email,'.($customer?->id ?? $customer)],
            'mobile' => ['nullable', 'string', 'max:30'],
            'dob' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:30'],
            'passport_no' => ['nullable', 'string', 'max:80'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string'],
            'kyc_status' => ['nullable', 'string', 'max:50'],
            'status' => ['nullable', 'string', 'max:50'],
        ];
    }
}
