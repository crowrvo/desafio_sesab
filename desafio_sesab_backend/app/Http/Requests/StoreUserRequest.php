<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'name' => 'required|string',
            'cpf' => 'required|unique:users|regex:/(^\d{3}\.\d{3}\.\d{3}\-\d{2}$)/u',
            'email' => 'required|unique:users|email',
            'role_id' => 'required|exists:roles,id|numeric',
            'addresses' => 'required|min:1',
            "addresses.*.logradouro" => 'required|string',
            "addresses.*.cep" => 'required|regex:/(^\d{5}\-\d{3}$)/'
        ];
    }
}