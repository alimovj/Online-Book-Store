<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLanguageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'prefix' => 'required|string|max:5|unique:languages,prefix,' . $this->route('id'),
            'is_active' => 'required|boolean',
        ];
    }
}
