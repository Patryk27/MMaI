<?php

namespace App\Application\Interfaces\Http\Controllers\Backend;

use App\Application\Events\LoginAttempted;
use App\Application\Http\Controllers\Controller;
use App\Application\Interfaces\Http\Requests\Backend\Auth\SignIn;
use App\Core\Layout\Flasher;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcher;

class AuthorizationController extends Controller {

    private const AFTER_SIGN_IN_ROUTE = 'backend.dashboard.index';

    /** @var EventsDispatcher */
    private $eventsDispatcher;

    /** @var StatefulGuard */
    private $authGuard;

    /** @var Flasher */
    private $flasher;

    public function __construct(
        EventsDispatcher $eventsDispatcher,
        AuthManager $authManager,
        Flasher $flasher
    ) {
        $this->eventsDispatcher = $eventsDispatcher;
        $this->authGuard = $authManager->guard();
        $this->flasher = $flasher;
    }

    /**
     * @return mixed
     */
    public function in() {
        if ($this->authGuard->check()) {
            return redirect()->route(self::AFTER_SIGN_IN_ROUTE);
        }

        return view('backend.views.auth.in');
    }

    /**
     * @param SignIn $request
     * @return mixed
     */
    public function doIn(SignIn $request) {
        if ($this->authGuard->check()) {
            return redirect()->route(self::AFTER_SIGN_IN_ROUTE);
        }

        $loginSucceeded = $this->authGuard->attempt([
            'login' => $request->get('login'),
            'password' => $request->get('password'),
        ]);

        $this->eventsDispatcher->dispatch(
            new LoginAttempted([
                'login' => $request->get('login'),
                'successful' => $loginSucceeded,
            ])
        );

        if ($loginSucceeded) {
            return redirect()->route(self::AFTER_SIGN_IN_ROUTE);
        } else {
            $this->flasher->flashError('No such account exists.');

            return redirect()->route('backend.auth.do-in');
        }
    }

    /**
     * @return mixed
     */
    public function out() {
        $this->authGuard->logout();
        $this->flasher->flashSuccess('You have been signed out.');

        return redirect('/');
    }

}
