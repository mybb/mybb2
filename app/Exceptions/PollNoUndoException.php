<?php

namespace MyBB\Core\Exceptions;

use \Exception;

class PollNoUndoException extends Exception
{

	protected $message = 'errors.poll_nothing_to_undo';

	public function __construct($message = null, $code = 0, \Exception $previous = null)
	{
		if ($message === null) {
			$message = trans($this->message);
		}

		parent::__construct($message, $code, $previous);
	}
}
