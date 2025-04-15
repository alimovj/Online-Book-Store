<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'translations' => 'required|array|min:1',
            'translations.*.title' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
        ];
    }
}
