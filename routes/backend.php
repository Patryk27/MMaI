<?php

use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\PagesController;
use App\Http\Controllers\Backend\SignInController;

// -- administration panel -- //

// /backend
Route::group(['prefix' => 'backend'], function () {
    // /backend/auth
    Route::group(['prefix' => 'auth'], function () {
        // /backend/auth/in
        Route::get('in', SignInController::class . '@in')
            ->name('backend.auth.in');

        // /backend/auth/in
        Route::post('in', SignInController::class . '@doIn')
            ->name('backend.auth.do-in');

        // /backend/auth/out
        Route::post('out', SignInController::class . '@out')
            ->name('backend.auth.out');
    });

    Route::group(['middleware' => 'auth'], function () {
        // /backend/dashboard
        Route::group(['prefix' => 'dashboard'], function () {
            Route::get('/', DashboardController::class . '@index')
                ->name('backend.dashboard.index');
        });

        // /backend/search
        Route::get('search', PagesController::class . '@search')
            ->name('backend.pages.search');

        // /backend/pages
        Route::resource('pages', PagesController::class, [
            'as' => 'backend',
        ]);

        // /backend/posts
        Route::group(['prefix' => 'posts'], function () {
            // /backend/posts/index
            Route::get('index')
                ->name('backend.posts.index');
        });

        // /backend/tags
        Route::group(['prefix' => 'tags'], function () {
            // /backend/tags/index
            Route::get('index')
                ->name('backend.tags.index');
        });

        // /backend/about
        Route::group(['prefix' => 'about'], function () {
            // /backend/about/index
            Route::get('index')
                ->name('backend.about.index');
        });
    });
});