<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Controllers\Auth;

use Illuminate\Http\Request;
use MyBB\Core\Http\Controllers\AbstractController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use DaveJamesMiller\Breadcrumbs\Manager as Breadcrumbs;

class LoginController extends AbstractController
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * @var Breadcrumbs
     */
    protected $breadcrumbs;

    /**
     * Create a new controller instance.
     *
     * @param Breadcrumbs $breadcrumbs
     */
    public function __construct(Breadcrumbs $breadcrumbs)
    {
        $this->breadcrumbs = $breadcrumbs;
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        $this->breadcrumbs->setCurrentRoute('auth.login');

        return view('member.login');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username() : string
    {
        return 'name';
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return bool
     */
    protected function attemptLogin(Request $request) : bool
    {
        $credentials = $this->credentials($request);

        //First check username, password combination, if it fails
        if ($this->guard()->attempt(
            $credentials,
            $request->has('remember')
        )
        ) {
            //Username, password match
            return true;
        } else {
            //check email, password combination
            return $this->guard()->attempt(
                [
                    'email' => $credentials['name'],
                    'password' => $credentials['password']
                ],
                $request->has('remember')
            );
        }
    }
}
