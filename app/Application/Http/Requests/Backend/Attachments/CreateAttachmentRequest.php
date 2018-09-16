<?php

namespace App\Application\Http\Requests\Backend\Attachments;

use Illuminate\Foundation\Http\FormRequest;

class CreateAttachmentRequest extends FormRequest
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
            'attachment' => 'file',
        ];
    }

}
