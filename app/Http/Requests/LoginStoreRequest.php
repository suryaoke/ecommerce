<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class LoginStoreRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'email' => 'required|email|unique:users',
            'password' => 'required|string',

        ];
    }
    #[Override]
    public function attributes()
    {
        return [
            'email' => 'Email',
            'password' => 'kata Sandi',
        ];
    }
}
