<?php

namespace App\Application\Http\Requests\Backend\Pages;

use Illuminate\Foundation\Http\FormRequest;

class CreatePageRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'website_id' => 'numeric',

            'title' => 'string',
            'lead' => ['nullable', 'string'],
            'content' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],

            'type' => 'required',
            'status' => 'required',

            'url' => ['nullable', 'string', 'regex:/^[a-zA-Z0-9\-\/]*$/'],
            'tag_ids' => ['nullable', 'array'],

            // @todo rename to just "attachments"? if so, rename also the "tag_ids"
            'attachment_ids' => ['nullable', 'array'],
        ];
    }
}
