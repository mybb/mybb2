<?php

namespace MyBB\Core\Captcha;

use MyBB\Settings\Store;
use MyBB\Core\Captcha\AYAH\AYAH;

class CaptchaAyah implements CaptchaInterface {
	private $ayah;
	private $settings;

	public function __construct(Store $settings)
	{
		$this->settings = $settings;

		// Set up AYAH
		$this->ayah = new AYAH([
			'publisher_key' => $this->settings->get('captcha.ayah_public_key'),
			'scoring_key' => $this->settings->get('captcha.ayah_private_key')
		]);
	}

	public function render()
	{
		return $this->ayah->getPublisherHTML();
	}

	public function validate()
	{
		return $this->ayah->scoreResult();
	}
}