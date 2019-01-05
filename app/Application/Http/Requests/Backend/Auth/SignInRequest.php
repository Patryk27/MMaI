<?php

namespace App\Application\Http\Requests\Backend\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SignInRequest extends FormRequest {
    /**
     * @return bool
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array {
        return [
            'login' => 'required',
            'password' => 'required',
        ];
    }
}
