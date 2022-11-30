<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'string',
            'cpf' => [Rule::unique('users', 'cpf')->ignore($this->user->cpf, 'cpf'), 'regex:/(^\d{3}\.\d{3}\.\d{3}\-\d{2}$)/u'],
            'email' => [Rule::unique('users', 'email')->ignore($this->user->email, 'email'), 'email'],
            'role_id' => 'exists:roles,id|numeric',
            'addresses' => 'array|min:1',
            "addresses.*.logradouro" => 'string',
            "addresses.*.cep" => 'regex:/(^\d{5}\-\d{3}$)/',
            "addresses.*.id" => 'required|numeric'
        ];
    }
}