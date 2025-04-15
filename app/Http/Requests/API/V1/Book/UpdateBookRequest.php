<?php
namespace App\Http\Requests\API\V1\Book;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'author' => 'sometimes|required|string|max:255',
            'price' => 'sometimes|required|numeric|min:0',

            'translations' => 'sometimes|required|array|min:1',
            'translations.*.title' => 'required|string|min:3|max:255',
            'translations.*.description' => 'nullable|string',

            'category_ids' => 'sometimes|required|array|min:1',
            'category_ids.*' => 'exists:categories,id',

            'image' => 'nullable|image|max:2048',
        ];
    }
}
