<?php

namespace MyBB\Core\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ConversationNotFoundException extends NotFoundHttpException
{

	protected $message = 'errors.conversation_not_found';

	public function __construct($message = null, \Exception $previous = null, $code = 0)
	{
		if ($message === null) {
			$message = trans($this->message);
		}

		parent::__construct($message, $previous, $code);
	}
}
