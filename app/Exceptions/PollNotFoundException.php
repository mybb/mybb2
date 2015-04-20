<?php

namespace MyBB\Core\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PollNotFoundException extends NotFoundHttpException
{

	protected $message = 'errors.poll_not_found';

	public function __construct($message = null, \Exception $previous = null, $code = 0)
	{
		if ($message === null) {
			$message = trans($this->message);
		}

		parent::__construct($message, $previous, $code);
	}
}
