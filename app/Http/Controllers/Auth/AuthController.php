<?php namespace MyBB\Core\Http\Controllers\Auth;

use DaveJamesMiller\Breadcrumbs\Manager as Breadcrumbs;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use MyBB\Auth\Contracts\Guard;
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
	 * @var Breadcrumbs
	 */
	private $breadcrumbs;

	/**
	 * @var Request
	 */
	private $request;

	/**
	 * Create a new authentication controller instance.
	 *
	 * @param Guard                                $auth
	 * @param \Illuminate\Contracts\Auth\Registrar $registrar
	 * @param Breadcrumbs                          $breadcrumbs
	 * @param Request                              $request
	 */
	public function __construct(Guard $auth, Registrar $registrar, Breadcrumbs $breadcrumbs, Request $request)
	{
		$this->auth = $auth;
		$this->registrar = $registrar;
		$this->breadcrumbs = $breadcrumbs;
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

		$validator = $this->registrar->validator($this->request->all());

		if ($validator->fails()) {
			$this->throwValidationException(
				$this->request,
				$validator
			);
		}

		$this->auth->login($this->registrar->create($this->request->all()));

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
			->withInput($this->request->only('username'))
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

		return redirect('/');
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
}
