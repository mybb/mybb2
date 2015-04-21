<?php

namespace MyBB\Core\Exceptions;

use \Exception;

class ConversationCantSendToSelfException extends Exception
{
	/**
	 * @var string
	 */
	protected $message = 'errors.conversation_cant_send_to_self';

	/**
	 * @param null      $message
	 * @param int       $code
	 * @param Exception $previous
	 */
	public function __construct($message = null, $code = 0, \Exception $previous = null)
	{
		if ($message === null) {
			$message = trans($this->message);
		}

		parent::__construct($message, $code, $previous);
	}
}
