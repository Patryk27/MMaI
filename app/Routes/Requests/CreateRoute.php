<?php

namespace App\Routes\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class CreateRoute extends FormRequest {

    /**
     * @return array
     */
    public function rules(): array {
        return [
            'subdomain' => 'string', // @todo add regex validation
            'url' => 'string', // @todo add regex validation
            'model_type' => 'string',
            'model_id' => 'numeric',
        ];
    }

}
