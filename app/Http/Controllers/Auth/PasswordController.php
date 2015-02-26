<?php namespace MyBB\Core\Http\Controllers\Auth;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\ResetsPasswords;
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

	use ResetsPasswords;

	private $redirectTo = '';

	/**
	 * Create a new password controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard          $auth
	 * @param  \Illuminate\Contracts\Auth\PasswordBroker $passwords
	 *
	 * @return void
	 */
	public function __construct(Guard $auth, PasswordBroker $passwords)
	{
		parent::__construct($auth);

		$this->auth = $auth;
		$this->passwords = $passwords;

		$this->middleware('guest');
	}

	protected function getEmailSubject()
	{
		return trans('passwords.email_subject');
	}
}
