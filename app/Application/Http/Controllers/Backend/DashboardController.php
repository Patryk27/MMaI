<?php

namespace App\Application\Http\Controllers\Backend;

use App\Application\Http\Controllers\Controller;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    /**
     * @param Request $request
     * @return ViewContract
     */
    public function index(Request $request)
    {
        return view('backend.pages.dashboard.index', [
            'user' => $request->user(),
        ]);
    }

}
