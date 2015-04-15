<?php

namespace MyBB\Core\Exceptions;

use \Exception;

class ConversationCantSendToSelfException extends Exception
{

	protected $message = 'errors.conversation_cant_send_to_self';

	public function __construct($message = null, $code = 0, \Exception $previous = null)
	{
		if ($message === null) {
			$message = trans($this->message);
		}

		parent::__construct($message, $code, $previous);
	}
}
