<?php

namespace MyBB\Core\Exceptions;

use \RuntimeException;

class PermissionImplementInterfaceException extends RuntimeException
{
	/**
	 * @var string
	 */
	protected $message = 'errors.permission_implement_interface';

	/**
	 * @param string     $class
	 * @param int        $code
	 * @param \Exception $previous
	 */
	public function __construct($class, $code = 0, \Exception $previous = null)
	{
		$message = trans($this->message, compact('class'));

		parent::__construct($message, $code, $previous);
	}
}
