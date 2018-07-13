<?php

use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\PagesController;
use App\Http\Controllers\Backend\SignInController;
use App\Http\Controllers\Backend\TagsController;

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

        // /backend/pages/search
        Route::get('pages/search', PagesController::class . '@search')
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

        // /backend/tags/search
        Route::get('tags/search', TagsController::class . '@search')
            ->name('backend.tags.search');

        // /backend/tags
        Route::resource('tags', TagsController::class, [
            'as' => 'backend',
        ]);

        // /backend/about
        Route::get('about')
            ->name('backend.about.index');
    });
});