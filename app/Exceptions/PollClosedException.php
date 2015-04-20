<?php

namespace MyBB\Core\Exceptions;

use \Exception;

class PollClosedException extends Exception
{

	protected $message = 'errors.poll_is_closed';

	public function __construct($message = null, $code = 0, \Exception $previous = null)
	{
		if ($message === null) {
			$message = trans($this->message);
		}

		parent::__construct($message, $code, $previous);
	}
}
