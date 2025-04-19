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
           'book_id' => 'required|exists:books,id',
            'currency' => 'required|in:UZS,USD,RUB',
        ];
    }
}
