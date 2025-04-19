<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTranslationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'key' => 'required|string|unique:translations,key',
            'value' => 'required|string',
            'locale' => 'required|string|exists:languages,prefix',
            'is_active' => 'required|boolean',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
