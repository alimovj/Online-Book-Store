<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTranslationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'key' => 'sometimes|string|unique:translations,key,' . $this->translation->id,
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
