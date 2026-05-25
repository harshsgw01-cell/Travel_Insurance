<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreClaimRequest extends FormRequest
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
            'policy_id' => ['required', 'exists:policies,id'],
            'traveler_id' => ['nullable', 'exists:travelers,id'],
            'claim_type' => ['required', 'string', 'max:100'],
            'incident_date' => ['required', 'date'],
            'amount_claimed' => ['required', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'string'],
        ];
    }
}
