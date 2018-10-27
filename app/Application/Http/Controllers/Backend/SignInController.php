<?php

namespace App\Application\Http\Controllers\Backend;

use App\Application\Http\Controllers\Controller;
use App\Application\Http\Requests\Backend\Auth\SignInRequest;
use App\Core\Layout\Flasher;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\StatefulGuard as StatefulGuardContract;

class SignInController extends Controller
{

    private const AFTER_SIGN_IN_ROUTE = 'backend.dashboard.index';

    /**
     * @var StatefulGuardContract
     */
    private $authGuard;

    /**
     * @var Flasher
     */
    private $flasher;

    /**
     * @param AuthManager $authManager
     * @param Flasher $flasher
     */
    public function __construct(
        AuthManager $authManager,
        Flasher $flasher
    ) {
        $this->authGuard = $authManager->guard();
        $this->flasher = $flasher;
    }

    /**
     * @return mixed
     */
    public function in()
    {
        if ($this->authGuard->check()) {
            return redirect()->route(self::AFTER_SIGN_IN_ROUTE);
        }

        return view('backend.pages.auth.in');
    }

    /**
     * @param SignInRequest $request
     * @return mixed
     */
    public function doIn(SignInRequest $request)
    {
        if ($this->authGuard->check()) {
            return redirect()->route(self::AFTER_SIGN_IN_ROUTE);
        }

        $loginSucceeded = $this->authGuard->attempt([
            'login' => $request->get('login'),
            'password' => $request->get('password'),
        ]);

        if ($loginSucceeded) {
            return redirect()->route(self::AFTER_SIGN_IN_ROUTE);
        } else {
            $this->flasher->flashError('No account with given credentials was found.');

            return redirect()->route('backend.auth.do-in');
        }
    }

    /**
     * @return mixed
     */
    public function out()
    {
        $this->authGuard->logout();
        $this->flasher->flashSuccess('You have been signed out.');

        return redirect('/');
    }

}
