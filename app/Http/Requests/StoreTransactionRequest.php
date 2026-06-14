<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bank_id'             => ['required', 'exists:banks,id'],
            'date'                => ['required', 'date'],
            'type'                => ['required', 'in:credit,cash_withdrawal,bank_transfer'],
            'party_name'          => ['nullable', 'string', 'max:255'],
            'amount'              => ['required', 'numeric', 'min:0.01'],
            'transfer_to_bank_id' => [
                'nullable',
                'required_if:type,bank_transfer',
                'exists:banks,id',
                'different:bank_id',
            ],
            'notes'               => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'transfer_to_bank_id.required_if' => 'Please select the bank to transfer funds to.',
            'transfer_to_bank_id.different'   => 'Transfer bank must be different from source bank.',
            'amount.min'                      => 'Amount must be greater than zero.',
        ];
    }
}
