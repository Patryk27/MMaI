<?php

use App\Http\Controllers\Frontend\DispatchController;

// -- catch-all for all slugs -- //
Route::get('{url}', DispatchController::class . '@show')
    ->where('url', '(.*)');