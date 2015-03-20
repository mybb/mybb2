<?php

namespace MyBB\Core\Captcha;

use Illuminate\Http\Request;
use MyBB\Settings\Store;
use Greggilbert\Recaptcha\Recaptcha;
use Greggilbert\Recaptcha\Service\CheckRecaptchaV2;

class CaptchaNocaptcha implements CaptchaInterface {
	private $nocaptcha;
	private $service;
	private $settings;
	private $request;

	public function __construct(Store $settings, Request $request)
	{
		$this->settings = $settings;
		$this->request = $request;

		// Set up Recaptcha/Nocaptcha - we're not using the service provider as we need to change config options
		$this->service = new CheckRecaptchaV2();
		$this->nocaptcha = new Recaptcha($this->service, [
			'public_key' => $this->settings->get('captcha.nocaptcha_public_key'),
			'private_key' => $this->settings->get('captcha.nocaptcha_private_key'),
			'template' => 'captcha.nocaptcha',
			'options' => [
				'lang' => $this->settings->get('user.lang', 'en'),
			]
		]);
	}

	public function render()
	{
		return $this->nocaptcha->render();
	}

	public function validate()
	{
		// Dirty hack to make use of our key instead of the config one
		app('config')->set('recaptcha.private_key', $this->settings->get('captcha.nocaptcha_private_key'));
		return $this->service->check(null, $this->request->get('g-recaptcha-response'));
	}
}