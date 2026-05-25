<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePolicyRequest extends FormRequest
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
            'plan_id' => ['required', 'exists:plans,id'],
            'agent_id' => ['nullable', 'exists:users,id'],
            'policy_type' => ['required', 'string', 'max:80'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'destination_country' => ['required', 'string', 'max:120'],
            'trip_type' => ['nullable', 'string', 'max:80'],
            'premium_amount' => ['required', 'numeric', 'min:0'],
            'tax_amount' => ['nullable', 'numeric', 'min:0'],
            'traveler_ids' => ['required', 'array', 'min:1'],
            'traveler_ids.*' => ['exists:travelers,id'],
        ];
    }
}
