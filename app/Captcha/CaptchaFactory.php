<?php

namespace MyBB\Core\Captcha;

use MyBB\Settings\Store;
use Illuminate\Contracts\Foundation\Application;

class CaptchaFactory implements CaptchaInterface {
	private $settings;
	private $app;

	const NONE = 'none';
	const MYBB = 'mybb';
	const AYAH = 'ayah';
	const RECAPTCHA = 'recaptcha';
	const NOCAPTCHA = 'nocaptcha';

	public function __construct(Store $settings, Application $app)
	{
		$this->settings = $settings;
		$this->app = $app;
	}

	public function render($captcha = false)
	{
		$captcha = $this->getCaptchaClass($captcha);

		// Not supported
		if($captcha === null)
		{
			return '';
		}

		return $captcha->render();
	}

	public function validate($captcha = false)
	{
		$captcha = $this->getCaptchaClass($captcha);

		// Not supported
		if($captcha === null)
		{
			return true;
		}

		return $captcha->validate();
	}

	// Not used for the Factory, the function is mainly used when generating the correct captcha
	public function supported()
	{
		return true;
	}

	private function getCaptchaClass($captchaName)
	{
		if($captchaName == false)
		{
			$captchaName = $this->settings->get('captcha.method', static::NONE);
		}

		if($captchaName === static::NONE)
		{
			return null;
		}

		$captchaClass = 'MyBB\\Core\\Captcha\\Captcha' . ucfirst($captchaName);

		if(!class_exists($captchaClass))
		{
			return null;
		}

		$captcha = $this->app->make($captchaClass);

		if(!$captcha || !($captcha instanceof CaptchaInterface) || !$captcha->supported())
		{
			throw new \RuntimeException("Failed to load Captcha Class '{$captchaClass}'");
		}

		return $captcha;
	}
}