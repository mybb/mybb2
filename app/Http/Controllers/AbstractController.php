<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class AbstractController extends BaseController
{
	use DispatchesJobs;
	use ValidatesRequests {
		ValidatesRequests::getRedirectUrl as parentGetRedirectUrl;
	}

	/**
	 * @var string
	 */
	protected $failedValidationRedirect = '';

	/**
	 * @return string
	 */
	protected function getRedirectUrl()
	{
		if (!empty($this->failedValidationRedirect)) {
			return $this->failedValidationRedirect;
		}

		return $this->parentGetRedirectUrl();
	}

	/**
	 * @param bool $redirect
	 *
	 * @return $this|bool
	 */
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
