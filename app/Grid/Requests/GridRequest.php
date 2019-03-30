<?php

namespace App\Grid\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class GridRequest extends FormRequest {

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
            'filters' => ['nullable', 'array'],
            'filters.*.field' => 'required',
            'filters.*.operator' => 'required',

            'sorting' => ['nullable', 'array'],
            'sorting.field' => 'required_with:sorting',
            'sorting.direction' => 'required_with:sorting',

            'pagination' => ['nullable', 'array'],
            'pagination.page' => 'required_with:pagination',
            'pagination.perPage' => 'required_with:pagination',
        ];
    }

}
