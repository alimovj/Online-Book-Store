<?php 
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => ['sometimes', 'string', 'min:3'],
            'email' => ['sometimes', 'email', 'unique:users,email,' . $this->route('id')],
            'password' => ['nullable', 'min:6'],
        ];
    }
}
