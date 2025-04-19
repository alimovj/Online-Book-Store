<?php

namespace App\Http\Requests\Currency;

use Illuminate\Foundation\Http\FormRequest;

class ConvertCurrencyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0',
            'from' => 'required|string|exists:currencies,code',
            'to' => 'required|string|exists:currencies,code',
        ];
    }
}
