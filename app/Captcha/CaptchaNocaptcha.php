<?php

namespace MyBB\Core\Captcha;

use Greggilbert\Recaptcha\Recaptcha;
use Greggilbert\Recaptcha\Service\CheckRecaptchaV2;
use Illuminate\Http\Request;
use MyBB\Settings\Store;

class CaptchaNocaptcha implements CaptchaInterface
{
	/**
	 * @var Recaptcha
	 */
	private $nocaptcha;

	/**
	 * @var CheckRecaptchaV2
	 */
	private $service;

	/**
	 * @var Store
	 */
	private $settings;

	/**
	 * @var Request
	 */
	private $request;

	/**
	 * @param Store   $settings
	 * @param Request $request
	 */
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
				// As an id should be unique but we may need more than one captcha per page (modals)
				// we simply generate a random id
				'id' => str_random()
			]
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function render()
	{
		return $this->nocaptcha->render();
	}

	/**
	 * {@inheritdoc}
	 */
	public function validate()
	{
		$value = $this->request->get('g-recaptcha-response');

		if (empty($value)) {
			return false;
		}

		// Dirty hack to make use of our key instead of the config one
		app('config')->set('recaptcha.private_key', $this->settings->get('captcha.nocaptcha_private_key'));
		app('config')->set('recaptcha.driver', 'curl');

		return $this->service->check(null, $value);
	}

	/**
	 * {@inheritdoc}
	 */
	public function supported()
	{
		// NoCaptcha is supported when we have a public and private key

		if ($this->settings->get('captcha.nocaptcha_public_key', '') == ''
			|| $this->settings->get('captcha.nocaptcha_private_key', '') == ''
		) {
			return false;
		}

		return true;
	}
}
