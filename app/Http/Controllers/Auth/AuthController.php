<?php

namespace MyBB\Core\Http\Controllers\Auth;

use Breadcrumbs;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use MyBB\Core\Http\Controllers\Controller;

class AuthController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | MyBB Authentication Manager
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. Trait methods have been changes to accomodate
    | MyBB Templates
    |
    */

    use AuthenticatesAndRegistersUsers;

    /**
     * Create a new authentication controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\Guard     $auth
     * @param  \Illuminate\Http\Request             $request
     * @param  \Illuminate\Contracts\Auth\Registrar $registrar
     *
     */
    public function __construct(Guard $auth, Request $request, Registrar $registrar)
    {
        parent::__construct($auth, $request);

        $this->auth = $auth;
        $this->registrar = $registrar;

        $this->middleware('guest', ['except' => 'getLogout']);
    }


    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSignup()
    {
        Breadcrumbs::setCurrentRoute('auth.signup');

        return view('member.signup');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param \Illuminate\Foundation\Http\FormRequest|Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function postSignup(Request $request)
    {
        $this->failedValidationRedirect = url('auth/signup');

        $captcha = $this->checkCaptcha();
        if ($captcha !== true) {
            return $captcha;
        }

        $validator = $this->registrar->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request,
                $validator
            );
        }

        $this->auth->login($this->registrar->create($request->all()));

        return redirect($this->redirectPath());
    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        Breadcrumbs::setCurrentRoute('auth.login');

        return view('member.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $this->failedValidationRedirect = url('auth/login');

        $this->validate(
            $request,
            [
                'username' => 'required',
                'password' => 'required',
            ]
        );

        $credentials = $request->only('username', 'password');

        if ($this->auth->attempt(
            ['name' => $credentials['username'], 'password' => $credentials['password']],
            $request->input('remember_me')
        )
        ) {
            return redirect()->intended($this->redirectPath());
        }

        return redirect('/auth/login')
            ->withInput($request->only('username'))
            ->withErrors(
                [
                    'username' => trans('member.invalidCredentials'),
                ]
            );
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
        $this->auth->logout();

        return redirect('/');
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        return property_exists($this, 'redirectTo') ? $this->redirectTo : '/';
    }
}
