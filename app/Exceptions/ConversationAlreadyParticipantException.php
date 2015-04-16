<?php

namespace MyBB\Core\Exceptions;

use \Exception;

class ConversationAlreadyParticipantException extends Exception
{

	protected $message = 'errors.conversation_already_participant';

	public function __construct($message = null, $code = 0, \Exception $previous = null)
	{
		if ($message === null) {
			$message = trans($this->message);
		}

		parent::__construct($message, $code, $previous);
	}
}
