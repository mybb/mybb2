<?php

/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */


namespace MyBB\Core\Exceptions;

use Exception;

class PermissionDeniedException extends Exception
{
    public function __construct($message = null, $code = 403, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
