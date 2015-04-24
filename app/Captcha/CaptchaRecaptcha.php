<?php

namespace MyBB\Core\Captcha;

use Greggilbert\Recaptcha\Recaptcha;
use Greggilbert\Recaptcha\Service\CheckRecaptcha;
use Illuminate\Config\Repository;
use Illuminate\Http\Request;
use MyBB\Settings\Store;

class CaptchaRecaptcha implements CaptchaInterface
{
	/**
	 * @var Recaptcha
	 */
	private $recaptcha;

	/**
	 * @var CheckRecaptcha
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
	 * @var Repository
	 */
	private $config;

	/**
	 * @param Store      $settings
	 * @param Request    $request
	 * @param Repository $config
	 */
	public function __construct(Store $settings, Request $request, Repository $config)
	{
		$this->settings = $settings;
		$this->request = $request;
		$this->config = $config;

		// Set up Recaptcha - we're not using the service provider as we need to change config options
		$this->service = new CheckRecaptcha();
		$this->recaptcha = new Recaptcha($this->service, [
			'public_key' => $this->settings->get('captcha.recaptcha_public_key'),
			'private_key' => $this->settings->get('captcha.recaptcha_private_key'),
			'template' => 'captcha.recaptcha',
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
		return $this->recaptcha->render();
	}

	/**
	 * {@inheritdoc}
	 */
	public function validate()
	{
		$challenge = $this->request->get('recaptcha_challenge_field');
		$value = $this->request->get('recaptcha_response_field');

		if (empty($challenge) || empty($value)) {
			return false;
		}

		// Dirty hack to make use of our key instead of the config one
		$this->config->set('recaptcha.private_key', $this->settings->get('captcha.recaptcha_private_key'));

		return $this->service->check($challenge, $value);
	}

	/**
	 * {@inheritdoc}
	 */
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
