<?php namespace MyBB\Core\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use MyBB\Auth\Contracts\Guard;
use Settings;
use View;

abstract class Controller extends BaseController
{

	use DispatchesCommands, ValidatesRequests
	{
		ValidatesRequests::getRedirectUrl as parentGetRedirectUrl;
	}

	protected $failedValidationRedirect = '';

	public function __construct(Guard $guard, Request $request)
	{
		app()->setLocale(Settings::get('user.language', 'en'));

		View::share('auth_user', $guard->user());

		if($guard->check())
		{
			$guard->user()->update([
				                       'last_visit' => new \DateTime(),
				                       'last_page' => $request->path()
			                       ]);
		}

		$langDir = [
			'left' => 'left',
			'right' => 'right'
		];
		if(trans('general.direction') == 'rtl')
		{
			$langDir['left'] = 'right';
			$langDir['right'] = 'left';
		}

		View::share('langDir', $langDir);
	}

	protected function getRedirectUrl()
	{
		if(!empty($this->failedValidationRedirect))
		{
			return $this->failedValidationRedirect;
		}

		return $this->parentGetRedirectUrl();
	}

	protected function checkCaptcha($redirect = true)
	{
		$valid = app()->make('MyBB\Core\Captcha\CaptchaFactory')->validate();

		if($valid)
		{
			return true;
		}

		if($redirect)
		{
			return redirect($this->getRedirectUrl())->withErrors([
				'captcha' => trans('errors.invalidCaptcha'),
			]);
		}

		return false;
	}
}
