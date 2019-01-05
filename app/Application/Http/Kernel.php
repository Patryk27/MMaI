<?php

namespace App\Application\Http;

use App\Application\Http\Middleware\LogRequest;
use App\Application\Http\Middleware\TrimStrings;
use App\Application\Http\Middleware\VerifyIfInstalled;
use Fideloper\Proxy\TrustProxies;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

final class Kernel extends HttpKernel {
    /** @var string[] */
    protected $middleware = [
        CheckForMaintenanceMode::class,
        ValidatePostSize::class,
        ConvertEmptyStringsToNull::class,
        TrustProxies::class,
        TrimStrings::class,
        LogRequest::class,
    ];

    /** @var string[][] */
    protected $middlewareGroups = [
        'web' => [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            VerifyIfInstalled::class,
        ],

        'web:api' => [
            'auth',
        ],

        'web:backend' => [
            // Nottin' here - left for consistency
        ],

        'web:frontend' => [
            Middleware\SetupLocale::class,
        ],
    ];

    /** @var string[] */
    protected $routeMiddleware = [
        'auth' => Authenticate::class,
        'bindings' => SubstituteBindings::class,
        'can' => Authorize::class,
    ];
}
