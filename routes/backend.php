<?php

use App\Application\Http\Controllers\Backend\Analytics\RequestsController as AnalyticsRequestsController;
use App\Application\Http\Controllers\Backend\AnalyticsController;
use App\Application\Http\Controllers\Backend\AuthorizationController;
use App\Application\Http\Controllers\Backend\DashboardController;
use App\Application\Http\Controllers\Backend\PagesController;
use App\Application\Http\Controllers\Backend\TagsController;

Route::domain('backend.' . env('APP_DOMAIN'))->group(function () {
    Route::redirect('/', 'auth');
    Route::redirect('auth', 'auth/in');

    // /auth
    Route::prefix('auth')->group(function () {
        // GET /auth/in
        Route::get('in', AuthorizationController::class . '@in')
            ->name('backend.auth.in');

        // POST /auth/in
        Route::post('in', AuthorizationController::class . '@doIn')
            ->name('backend.auth.do-in');

        // POST /auth/out
        Route::post('out', AuthorizationController::class . '@out')
            ->name('backend.auth.out');
    });

    Route::group(['middleware' => 'auth'], function () {
        // /dashboard
        Route::prefix('dashboard')->group(function () {
            Route::get('/', DashboardController::class . '@index')
                ->name('backend.dashboard.index');
        });

        // /analytics
        Route::prefix('analytics')->group(function () {
            // GET /analytics
            Route::get('/', AnalyticsController::class . '@index')
                ->name('backend.analytics.index');

            // GET /analytics/requests
            Route::get('requests', AnalyticsRequestsController::class . '@index')
                ->name('backend.analytics.requests');
        });

        // /pages
        Route::prefix('pages')->group(function () {
            // GET /pages
            Route::get('/', PagesController::class . '@index')
                ->name('backend.pages.index');

            // GET /pages/create-page
            Route::get('create-page', PagesController::class . '@createPage')
                ->name('backend.pages.create-page');

            // GET /pages/create-post
            Route::get('create-post', PagesController::class . '@createPost')
                ->name('backend.pages.create-post');

            // GET /pages/:page
            Route::get('{page}', PagesController::class . '@edit')
                ->name('backend.pages.edit');
        });

        // /tags
        Route::prefix('tags')->group(function () {
            // GET /tags
            Route::get('/', TagsController::class . '@index')
                ->name('backend.tags.index');
        });
    });
});
