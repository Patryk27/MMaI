<?php

use App\Application\Http\Controllers\Backend\AnalyticsController;
use App\Application\Http\Controllers\Backend\AttachmentsController;
use App\Application\Http\Controllers\Backend\DashboardController;
use App\Application\Http\Controllers\Backend\PagesController;
use App\Application\Http\Controllers\Backend\SignInController;
use App\Application\Http\Controllers\Backend\TagsController;

Route::domain('backend.' . env('APP_DOMAIN'))->group(function () {
    Route::redirect('/', 'auth');
    Route::redirect('auth', 'auth/in');

    // /auth
    Route::group(['prefix' => 'auth'], function () {
        // /auth/in
        Route::get('in', SignInController::class . '@in')
            ->name('backend.auth.in');

        // /auth/in
        Route::post('in', SignInController::class . '@doIn')
            ->name('backend.auth.do-in');

        // /auth/out
        Route::post('out', SignInController::class . '@out')
            ->name('backend.auth.out');
    });

    Route::group(['middleware' => 'auth'], function () {
        // /dashboard
        Route::group(['prefix' => 'dashboard'], function () {
            Route::get('/', DashboardController::class . '@index')
                ->name('backend.dashboard.index');
        });

        // /analytics
        Route::group(['prefix' => 'analytics'], function () {
            // /analytics
            Route::get('/', AnalyticsController::class . '@index')
                ->name('backend.analytics.index');

            // /analytics/requests
            Route::get('requests', AnalyticsController::class . '@requests')
                ->name('backend.analytics.requests');

            // /analytics/requests/search
            Route::post('requests/search', AnalyticsController::class . '@searchRequests')
                ->name('backend.analytics.requests.search');
        });

        // /attachments
        Route::post('attachments', AttachmentsController::class . '@store')
            ->name('backend.attachments.store');

        // /pages
        Route::group(['prefix' => 'pages'], function () {
            // /pages/search
            Route::post('search', PagesController::class . '@search')
                ->name('backend.pages.search');

            // /pages/create-page
            Route::get('create-page', PagesController::class . '@createPage')
                ->name('backend.pages.create-page');

            // /pages/create-post
            Route::get('create-post', PagesController::class . '@createPost')
                ->name('backend.pages.create-post');
        });

        // /pages
        Route::resource('pages', PagesController::class, [
            'as' => 'backend',
        ]);

        // /tags/search
        Route::post('tags/search', TagsController::class . '@search')
            ->name('backend.tags.search');

        // /tags
        Route::resource('tags', TagsController::class, [
            'as' => 'backend',
        ]);
    });
});
