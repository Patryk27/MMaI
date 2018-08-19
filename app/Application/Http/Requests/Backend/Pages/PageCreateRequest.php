<?php

namespace App\Application\Http\Requests\Backend\Pages;

use Illuminate\Foundation\Http\FormRequest;

class PageCreateRequest extends FormRequest
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
            'page.type' => 'required',
            'pageVariants.*.language_id' => 'numeric',
            'pageVariants.*.url' => ['nullable', 'string', 'regex:/^[a-zA-Z0-9\-\/]*$/'],
            'pageVariants.*.title' => 'string',
            'pageVariants.*.tag_ids' => ['nullable', 'array'],
            'pageVariants.*.status' => 'string',
        ];
    }

}