<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
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
            'transaction_id' => ['nullable', 'string', 'max:255', 'unique:payments,transaction_id'],
            'gateway' => ['required', 'string', 'max:80'],
            'amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'status' => ['nullable', 'string', 'max:50'],
            'gateway_response' => ['nullable', 'array'],
        ];
    }
}
