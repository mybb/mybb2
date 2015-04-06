<?php namespace MyBB\Core\Http\Controllers\Auth;

use Breadcrumbs;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Http\Controllers\Controller;

class PasswordController extends Controller
{

	/*
	|--------------------------------------------------------------------------
	| Password Reset Controller
	|--------------------------------------------------------------------------
	|
	| This controller is responsible for handling password reset requests
	| and uses a simple trait to include this behavior. You're free to
	| explore this trait and override any methods you wish to tweak.
	|
	*/

	use ResetsPasswords
	{
		postEmail as parentPostEmail;
	}

	private $redirectTo = '';

	/**
	 * Create a new password controller instance.
	 *
	 * @param Guard $auth
	 * @param \Illuminate\Http\Request $request
	 * @param \Illuminate\Contracts\Auth\PasswordBroker $passwords
	 */
	public function __construct(Guard $auth, Request $request, PasswordBroker $passwords)
	{
		parent::__construct($auth, $request);

		$this->auth = $auth;
		$this->passwords = $passwords;

		$this->middleware('guest');

		Breadcrumbs::setCurrentRoute('auth.login');
	}

	public function postEmail(Request $request)
	{
		$this->failedValidationRedirect = url('password/email');

		return $this->parentPostEmail($request);
	}

	protected function getEmailSubject()
	{
		return trans('passwords.email_subject');
	}
}
