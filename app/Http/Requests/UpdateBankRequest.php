<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBankRequest extends FormRequest
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
            'name'              => 'required|string|max:255',
            'account_title'     => 'required|string|max:255',
            'account_number'    => 'nullable|string|max:255',
            'monthly_limit'     => 'nullable|numeric|min:0',
            'weekly_cash_limit' => 'nullable|numeric|min:0',
        ];
    }
}
