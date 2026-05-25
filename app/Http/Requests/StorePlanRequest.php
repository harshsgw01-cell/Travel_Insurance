<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePlanRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:80', 'unique:plans,code'],
            'policy_type' => ['required', 'string', 'max:80'],
            'base_premium' => ['required', 'numeric', 'min:0'],
            'coverage_amount' => ['required', 'numeric', 'min:0'],
            'min_age' => ['integer', 'min:0'],
            'max_age' => ['integer', 'min:0'],
            'max_family_members' => ['nullable', 'integer', 'min:1'],
            'covered_countries' => ['nullable', 'array'],
            'benefits' => ['nullable', 'array'],
            'add_ons' => ['nullable', 'array'],
            'is_active' => ['boolean'],
        ];
    }
}
