<?php

namespace MyBB\Core\Captcha;

interface CaptchaInterface {
	public function render();
	public function validate();
	public function supported();
}