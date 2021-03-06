<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Exceptions;

use RuntimeException;

class PermissionImplementInterfaceException extends RuntimeException
{
    /**
     * @var string
     */
    protected $message = 'errors.permission_implement_interface';

    /**
     * @param string $class
     * @param int $code
     * @param \Exception $previous
     */
    public function __construct(string $class, int $code = 0, \Exception $previous = null)
    {
        $message = trans($this->message, compact('class'));

        parent::__construct($message, $code, $previous);
    }
}
