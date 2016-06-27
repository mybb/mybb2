<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Controllers\Auth;

use DaveJamesMiller\Breadcrumbs\Manager as Breadcrumbs;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\Factory;
use MyBB\Core\Database\Models\Role;
use MyBB\Core\Database\Models\User;
use MyBB\Core\Http\Controllers\AbstractController as Controller;

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
     * @var AuthManager $auth
     */
    private $auth;

    /**
     * @var Breadcrumbs $breadcrumbs
     */
    private $breadcrumbs;

    /**
     * @var Factory $validator
     */
    private $validator;

    /**
     * @var Request $request
     */
    private $request;

    /**
     * Create a new authentication controller instance.
     *
     * @param AuthManager $auth
     * @param Breadcrumbs $breadcrumbs
     * @param Factory $validator
     * @param Request $request
     */
    public function __construct(AuthManager $auth, Breadcrumbs $breadcrumbs, Factory $validator, Request $request)
    {
        $this->auth = $auth;
        $this->breadcrumbs = $breadcrumbs;
        $this->validator = $validator;
        $this->request = $request;

        $this->middleware('guest', ['except' => 'getLogout']);
    }


    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSignup()
    {
        $this->breadcrumbs->setCurrentRoute('auth.signup');

        return view('member.signup');
    }

    /**
     * Handle a registration request for the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function postSignup()
    {
        $this->failedValidationRedirect = url('auth/signup');

        $captcha = $this->checkCaptcha();
        if ($captcha !== true) {
            return $captcha;
        }

        $validator = $this->validator($this->request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $this->request,
                $validator
            );
        }

        $this->auth->login($this->create($this->request->all()));

        return redirect($this->redirectPath());
    }

    /**
     * Show the application login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogin()
    {
        $this->breadcrumbs->setCurrentRoute('auth.login');

        return view('member.login');
    }

    /**
     * Handle a login request to the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function postLogin()
    {
        $this->failedValidationRedirect = url('auth/login');

        $this->validate($this->request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = $this->request->only('username', 'password');

        if ($this->auth->attempt(
            ['name' => $credentials['username'], 'password' => $credentials['password']],
            $this->request->input('remember_me')
        )
        ) {
            return redirect()->intended($this->redirectPath());
        }

        return redirect('/auth/login')
            ->withInput($this->request->only('username', 'remember_me'))
            ->withErrors([
                'username' => trans('member.invalidCredentials'),
            ]);
    }

    /**
     * Log the user out of the application.
     *
     * @return \Illuminate\Http\Response
     */
    public function getLogout()
    {
        $this->auth->logout();
        $cookie = cookie()->forget('quotes'); // Remove cookies

        return redirect('/')->withCookie($cookie);
    }

    /**
     * Get the post register / login redirect path.
     *
     * @return string
     */
    public function redirectPath()
    {
        $redirectUrl = $this->request->input('url');

        if (!empty($redirectUrl)) {
            return $redirectUrl;
        }

        return '/';
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return $this->validator->make($data, [
            'name'     => 'required|max:255|unique:users',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     *
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $user->roles()->attach(Role::where('role_slug', '=', 'user')->pluck('id'), ['is_display' => true]);

        return $user;
    }
}
