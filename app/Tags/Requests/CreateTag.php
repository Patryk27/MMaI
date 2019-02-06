<?php

namespace App\Tags\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CreateTag extends FormRequest {

    /**
     * @return array
     */
    public function rules(): array {
        return [
            'website_id' => 'required',
            'name' => 'required',
        ];
    }

}
