<?php

namespace App\Application\Http\Requests\Backend\Tags;

use Illuminate\Foundation\Http\FormRequest;

final class CreateTag extends FormRequest {

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
            'websiteId' => 'required',
            'name' => 'required',
        ];
    }

}
