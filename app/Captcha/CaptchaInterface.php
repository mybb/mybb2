<?php

namespace MyBB\Core\Captcha;

interface CaptchaInterface
{
	/**
	 * @return string
	 */
	public function render();

	/**
	 * @return bool
	 */
	public function validate();

	/**
	 * @return bool
	 */
	public function supported();
}
