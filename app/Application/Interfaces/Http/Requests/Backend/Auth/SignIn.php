<?php

namespace App\Application\Interfaces\Http\Requests\Backend\Auth;

use Illuminate\Foundation\Http\FormRequest;

final class SignIn extends FormRequest {

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
