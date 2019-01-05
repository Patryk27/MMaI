<?php

namespace App\Application\Http\Requests\Backend\Tags;

use Illuminate\Foundation\Http\FormRequest;

class CreateTagRequest extends FormRequest {
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
            'website_id' => 'required',
            'name' => 'required',
        ];
    }
}
