<?php

namespace App\Attachments\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CreateAttachment extends FormRequest {

    /**
     * @return array
     */
    public function rules(): array {
        return [
            'attachment' => 'file',
        ];
    }

}
