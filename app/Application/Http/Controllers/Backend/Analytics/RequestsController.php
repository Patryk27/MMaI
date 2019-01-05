<?php

namespace App\Application\Http\Controllers\Backend\Analytics;

use App\Application\Http\Controllers\Controller;

class RequestsController extends Controller {
    /**
     * @return mixed
     */
    public function index() {
        return view('backend.views.analytics.requests');
    }
}
