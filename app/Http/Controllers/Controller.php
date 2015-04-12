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

	use DispatchesCommands, ValidatesRequests {
		ValidatesRequests::getRedirectUrl as parentGetRedirectUrl;
	}

	protected $failedValidationRedirect = '';

	protected function getRedirectUrl()
	{
		if (!empty($this->failedValidationRedirect)) {
			return $this->failedValidationRedirect;
		}

		return $this->parentGetRedirectUrl();
	}

	protected function checkCaptcha($redirect = true)
	{
		$valid = app()->make('MyBB\Core\Captcha\CaptchaFactory')->validate();

		if ($valid) {
			return true;
		}

		if ($redirect) {
			return redirect($this->getRedirectUrl())->withInput()->withErrors([
				'captcha' => trans('errors.invalidCaptcha'),
			]);
		}

		return false;
	}
}
