<?php

namespace App\App\Http\Controllers\Backend;

use App\App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View as ViewContract;

class DashboardController extends Controller
{

    /**
     * @return ViewContract
     */
    public function index()
    {
        return view('backend.pages.dashboard.index');
    }

}