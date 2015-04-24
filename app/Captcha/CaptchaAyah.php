<?php

namespace MyBB\Core\Captcha;

use MyBB\AreYouAHuman\AYAH;
use MyBB\Settings\Store;

class CaptchaAyah implements CaptchaInterface
{
	/**
	 * @var AYAH
	 */
	private $ayah;

	/**
	 * @var Store
	 */
	private $settings;

	/**
	 * @param Store $settings
	 */
	public function __construct(Store $settings)
	{
		$this->settings = $settings;

		// Set up AYAH
		$this->ayah = new AYAH([
			'publisher_key' => $this->settings->get('captcha.ayah_public_key'),
			'scoring_key' => $this->settings->get('captcha.ayah_private_key')
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function render()
	{
		return $this->ayah->getPublisherHTML();
	}

	/**
	 * {@inheritdoc}
	 */
	public function validate()
	{
		return $this->ayah->scoreResult();
	}

	/**
	 * {@inheritdoc}
	 */
	public function supported()
	{
		// AYAH is supported when we have a public and private key

		if ($this->settings->get('captcha.ayah_public_key', '') == ''
			|| $this->settings->get('captcha.ayah_private_key', '') == ''
		) {
			return false;
		}

		return true;
	}
}
