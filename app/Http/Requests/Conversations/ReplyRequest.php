<?php
/**
 * Topic reply request.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Requests\Conversations;

use Illuminate\Contracts\Auth\Guard;
use MyBB\Core\Http\Requests\AbstractRequest;

class ReplyRequest extends AbstractRequest
{
    /**
     * The route to redirect to if validation fails.
     *
     * @var string
     */
    protected $redirectRoute = 'conversations.read';

    /**
     * @var Guard
     */
    private $guard;

    /**
     * @param Guard $guard
     */
    public function __construct(Guard $guard)
    {
        $this->guard = $guard;
    }

    /**
     * @return array
     */
    public function rules() : array
    {
        return [
            'message' => 'required',
        ];
    }

    /**
     * @return bool
     */
    public function authorize() : bool
    {
        //return $this->guard->check();
        return true; // TODO: In dev return, needs replacing for later...
    }

    /**
     * @return string
     */
    protected function getRedirectUrl() : string
    {
        return $this->redirector->getUrlGenerator()->route($this->redirectRoute, $this->route()->parameters());
    }
}
