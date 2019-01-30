<?php

namespace App\Application\Http\Requests\Backend\Tags;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateTag extends FormRequest {

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
            'name' => 'required',
        ];
    }

}
