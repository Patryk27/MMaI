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
            'attachment_ids' => 'array',

            // @todo rename to just `variants`
            'pageVariants.*.route' => ['nullable', 'string', 'regex:/^[a-zA-Z0-9\-\/]*$/'],
            'pageVariants.*.title' => 'string',
            'pageVariants.*.tag_ids' => ['nullable', 'array'],
            'pageVariants.*.status' => 'string',
        ];
    }

}
