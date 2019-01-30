<?php

namespace App\Application\Http\Requests\Backend\Pages;

use Illuminate\Foundation\Http\FormRequest;

final class UpdatePage extends FormRequest {

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
            'title' => 'string',
            'lead' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'url' => ['nullable', 'string', 'regex:/^[a-zA-Z0-9\-\/]*$/'],

            'type' => 'required',
            'status' => 'required',

            'tagIds' => ['nullable', 'array'],
            'attachmentIds' => ['nullable', 'array'],
        ];
    }

}
