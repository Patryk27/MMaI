<?php

namespace App\Application\Http\Controllers\Backend;

use App\Application\Http\Controllers\Controller;

class AnalyticsController extends Controller
{

    /**
     * @return mixed
     */
    public function index()
    {
        return view('backend.pages.analytics.index');
    }

}
