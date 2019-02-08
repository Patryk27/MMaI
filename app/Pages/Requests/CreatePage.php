<?php

namespace App\Pages\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CreatePage extends FormRequest {

    /**
     * @return array
     */
    public function rules(): array {
        return [
            'website_id' => 'numeric',

            'title' => 'string',
            'lead' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'url' => ['nullable', 'string', 'regex:/^[a-zA-Z0-9\-\/]*$/'],

            'type' => 'required',
            'status' => 'required',

            'tag_ids' => ['nullable', 'array'],
            'attachment_ids' => ['nullable', 'array'],
        ];
    }

    /**
     * @return bool
     */
    public function hasUrl(): bool {
        return strlen($this->get('url')) > 0;
    }

}
