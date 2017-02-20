<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Exceptions;

use RuntimeException;

class DateInvalidObjectException extends RuntimeException
{
    /**
     * @var string
     */
    protected $message = 'errors.invalid_date_object';

    /**
     * @param string $message
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct(string $message = null, int $code = 0, \Exception $previous = null)
    {
        if ($message === null) {
            $message = trans($this->message);
        }

        parent::__construct($message, $code, $previous);
    }
}
