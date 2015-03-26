<?php

namespace MyBB\Core\Captcha;

use Greggilbert\Recaptcha\Recaptcha;
use Greggilbert\Recaptcha\Service\CheckRecaptcha;
use Illuminate\Http\Request;
use MyBB\Settings\Store;

class CaptchaRecaptcha implements CaptchaInterface
{
	private $recaptcha;
	private $service;
	private $settings;
	private $request;

	public function __construct(Store $settings, Request $request)
	{
		$this->settings = $settings;
		$this->request = $request;

		// Set up Recaptcha - we're not using the service provider as we need to change config options
		$this->service = new CheckRecaptcha();
		$this->recaptcha = new Recaptcha($this->service, [
			'public_key' => $this->settings->get('captcha.recaptcha_public_key'),
			'private_key' => $this->settings->get('captcha.recaptcha_private_key'),
			'template' => 'captcha.recaptcha',
			'options' => [
				'lang' => $this->settings->get('user.lang', 'en'),
				// As an id should be unique but we may need more than one captcha per page (modals) we simply generate a random id
				'id' => str_random()
			]
		]);
	}

	public function render()
	{
		return $this->recaptcha->render();
	}

	public function validate()
	{
		$challenge = $this->request->get('recaptcha_challenge_field');
		$value = $this->request->get('recaptcha_response_field');

		if (empty($challenge) || empty($value)) {
			return false;
		}

		// Dirty hack to make use of our key instead of the config one
		app('config')->set('recaptcha.private_key', $this->settings->get('captcha.recaptcha_private_key'));

		return $this->service->check($challenge, $value);
	}

	public function supported()
	{
		// ReCaptcha is supported when we have a public and private key

		if ($this->settings->get('captcha.recaptcha_public_key', '') == ''
			|| $this->settings->get('captcha.recaptcha_private_key', '') == ''
		) {
			return false;
		}

		return true;
	}
}