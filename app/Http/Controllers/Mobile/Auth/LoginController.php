<?php

namespace App\Http\Controllers\Mobile\Auth;

use App\Events\UserBrowsingHistoryEvent;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;


class LoginController extends Controller
{
    use AuthenticatesUsers;

    // protected $redirectTo = '/mobile';


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectTo()
    {
        return URL::previous();
    }

    public function username()
    {
        if (request()->has('username')) {
            if (Validator::make(request()->all(), [
                'username' => 'required|string|regex:/^\d+$/',
            ])->passes()
            ) {
                return 'phone';
            } elseif (Validator::make(request()->all(), [
                'username' => 'required|string|email',
            ])->passes()
            ) {
                return 'email';
            }
        }
        return 'name';
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            'username' => 'bail|required|string',
            'password' => 'required|string',
        ]);
    }


    public function showLoginForm()
    {
        return view('mobile.auth.login');
    }


    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $users = User::where([
            $this->username() => $request->input('username'),
        ])->get();
        foreach ($users as $user) {
            $userData = $user->makeVisible('password')->toArray();
            if (Hash::check($request->input('password'), $userData['password'])) {
                if ($request->filled('remember')) {
                    Auth::login($user, true);
                } else {
                    Auth::login($user);
                }

                // user browsing history - initialization
                $loginController = new \App\Http\Controllers\Auth\LoginController();
                $loginController->initializeUserBrowsingHistoryCacheByUser($user);

                return $this->sendLoginResponse($request);
                break;
            }
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    // POST Log out
    public function logout(Request $request)
    {
        // user browsing history - expiration (firing an event)
        if (Auth::check()) {
            event(new UserBrowsingHistoryEvent(Auth::user(), true));
        }

        $this->guard()->logout();

        return redirect($this->redirectTo);
    }
}
