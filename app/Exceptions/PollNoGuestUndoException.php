<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Exceptions;

use \Exception;

class PollNoGuestUndoException extends Exception
{
	/**
	 * @var string
	 */
	protected $message = 'errors.poll_guest_undo';

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
