<?php
namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Auth middleware allaqachon himoyalagan bo'ladi
    }

    public function rules(): array
    {
        return [
            'book_id' => 'required|exists:books,id',
            'address' => 'required|string|min:5|max:255',
            'stock' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'book_id.required' => __('Book is required.'),
            'book_id.exists' => __('Selected book does not exist.'),
            'address.required' => __('Address is required.'),
            'stock.required' => __('Stock quantity is required.'),
        ];
    }
}
