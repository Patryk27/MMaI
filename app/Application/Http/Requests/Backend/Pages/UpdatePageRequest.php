<?php

namespace App\Application\Http\Requests\Backend\Pages;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePageRequest extends FormRequest
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
            'page.type' => 'string',

            'attachments' => 'array',

            // @todo rename to just `variants`
            'page_variants.*.route' => ['nullable', 'string', 'regex:/^[a-zA-Z0-9\-\/]*$/'],
            'page_variants.*.title' => 'string',
            'page_variants.*.tag_ids' => ['nullable', 'array'],
            'page_variants.*.status' => 'string',
        ];
    }

}
