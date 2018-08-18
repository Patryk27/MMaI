<?php

namespace App\Application\Http\Controllers\Backend;

use App\Application\Http\Controllers\Controller;
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