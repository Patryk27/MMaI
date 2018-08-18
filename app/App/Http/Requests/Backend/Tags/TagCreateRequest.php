<?php

namespace App\App\Http\Requests\Backend\Tags;

use Illuminate\Foundation\Http\FormRequest;

class TagCreateRequest extends FormRequest
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
            'name' => 'required',
            'language_id' => 'required',
        ];
    }

}