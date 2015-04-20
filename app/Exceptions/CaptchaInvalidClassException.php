<?php

namespace MyBB\Core\Exceptions;

use \RuntimeException;

class CaptchaInvalidClassException extends RuntimeException
{

	protected $message = 'errors.captcha_invalid_class';

	public function __construct($class, $code = 0, \Exception $previous = null)
	{
		$message = trans($this->message, compact('class'));

		parent::__construct($message, $code, $previous);
	}
}
