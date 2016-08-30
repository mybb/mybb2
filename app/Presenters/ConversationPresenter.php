<?php
/**
 * Forum presenter class.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Presenters;

use Illuminate\Contracts\Auth\Guard;
use McCool\LaravelAutoPresenter\BasePresenter;
use MyBB\Core\Database\Models\Conversation as ConversationModel;
use MyBB\Core\Database\Models\User;

class ConversationPresenter extends BasePresenter
{
    /** @var ConversationModel $wrappedObject */

    /**
     * @var Guard
     */
    private $guard;

    /**
     * @param ConversationModel $resource
     * @param Guard $guard
     */
    public function __construct(ConversationModel $resource, Guard $guard)
    {
        parent::__construct($resource);

        $this->guard = $guard;
    }

    /**
     * @return ConversationMessagePresenter
     */
    public function lastMessage()
    {
        if ($this->wrappedObject->lastMessage instanceof ConversationMessagePresenter) {
            return $this->wrappedObject->lastMessage;
        }

        return app()->make(\MyBB\Core\Presenters\ConversationMessagePresenter::class, [$this->wrappedObject->lastMessage]);
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function isUnread(User $user = null)
    {
        if ($user == null) {
            $user = $this->guard->user();
        }

        $participantData = $this->wrappedObject->participants->find($user->id)->pivot;

        if ($participantData->last_read == null) {
            return true;
        }

        if ($participantData->last_read < $this->wrappedObject->lastMessage->created_at) {
            return true;
        }

        return false;
    }
}
