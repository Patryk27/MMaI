<?php

namespace App\Application\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

final class Search extends FormRequest {

    /**
     * @return array
     */
    public function rules(): array {
        return [
            'query' => 'required',
        ];
    }

}
