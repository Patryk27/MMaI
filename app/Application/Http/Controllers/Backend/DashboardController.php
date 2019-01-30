<?php

namespace App\Application\Http\Controllers\Backend;

use App\Application\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller {

    /**
     * @param Request $request
     * @return View
     */
    public function index(Request $request) {
        return view('backend.views.dashboard.index', [
            'user' => $request->user(),
        ]);
    }

}
