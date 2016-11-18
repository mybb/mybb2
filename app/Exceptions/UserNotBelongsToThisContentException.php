<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Exceptions;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserNotBelongsToThisContentException extends NotFoundHttpException
{
    /**
     * @var string
     */
    protected $message = 'errors.user_not_belongs_to_content';

    /**
     * @param string $user
     * @param \Exception $previous
     * @param int $code
     */
    public function __construct($user, \Exception $previous = null, $code = 0)
    {
        $message = trans($this->message, compact('user'));

        parent::__construct($message, $previous, $code);
    }
}
