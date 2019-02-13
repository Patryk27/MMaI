<?php

namespace App\Attachments\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateAttachment extends FormRequest {

    /**
     * @return array
     */
    public function rules(): array {
        return [
            'name' => 'string',
        ];
    }

}
