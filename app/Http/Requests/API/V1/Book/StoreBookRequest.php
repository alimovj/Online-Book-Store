<?php
namespace App\Http\Requests\API\V1\Book;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'author' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',

            'translations' => 'required|array|min:1',
            'translations.*.title' => 'required|string|min:3|max:255',
            'translations.*.description' => 'nullable|string',

            'category_ids' => 'required|array|min:1',
            'category_ids.*' => 'exists:categories,id',

            'image' => 'nullable|image|max:2048',
        ];
    }
}
