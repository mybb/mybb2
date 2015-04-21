<?php namespace MyBB\Core\Http\Controllers\Auth;

use DaveJamesMiller\Breadcrumbs\Manager as Breadcrumbs;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Http\Controllers\AbstractController as Controller;

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

	use ResetsPasswords {
		postEmail as parentPostEmail;
	}

	/**
	 * @var string
	 */
	private $redirectTo = '';

	/**
	 * Create a new password controller instance.
	 *
	 * @param Guard                                     $auth
	 * @param \Illuminate\Contracts\Auth\PasswordBroker $passwords
	 * @param Breadcrumbs                               $breadcrumbs
	 */
	public function __construct(Guard $auth, PasswordBroker $passwords, Breadcrumbs $breadcrumbs)
	{
		$this->auth = $auth;
		$this->passwords = $passwords;

		$this->middleware('guest');

		$breadcrumbs->setCurrentRoute('auth.login');
	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Foundation\Auth\Response
	 */
	public function postEmail(Request $request)
	{
		$this->failedValidationRedirect = url('password/email');

		return $this->parentPostEmail($request);
	}

	/**
	 * @return string
	 */
	protected function getEmailSubject()
	{
		return trans('passwords.email_subject');
	}
}
