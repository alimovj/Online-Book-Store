<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Admin middleware buni oldin tekshiradi
    }

    public function rules(): array
    {
        return [
            'status' => 'required|in:canceled,on_way,delivered'
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => __('Status is required.'),
            'status.in' => __('Invalid status selected.')
        ];
    }
}
