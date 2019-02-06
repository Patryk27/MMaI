<?php

namespace App\Tags\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateTag extends FormRequest {

    /**
     * @return array
     */
    public function rules(): array {
        return [
            'name' => 'required',
        ];
    }

}
