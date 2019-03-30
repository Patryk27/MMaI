<?php

namespace App\Application\Interfaces\Http\Controllers\Api;

use App\Application\Http\Controllers\Controller;

class IndexController extends Controller {

    /**
     * @return array
     */
    public function index(): array {
        return [
            'message' => 'Hello, World!',
        ];
    }

}
