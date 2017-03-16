<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Exceptions;

use RuntimeException;

class TaskFailedException extends RuntimeException
{
    /**
     * @var string
     */
    protected $message = 'errors.task_failed';

    /**
     * @param null $message
     * @param \Exception $previous
     * @param int $code
     */
    public function __construct($message = null, int $code = 0, \Exception $previous = null)
    {
        if ($message === null) {
            $message = trans($this->message);
        }

        parent::__construct($message, $code, $previous);
    }
}
