<?php
/**
 * Forum presenter class.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Presenters;

use Illuminate\Auth\Guard;
use Illuminate\Database\DatabaseManager;
use McCool\LaravelAutoPresenter\BasePresenter;
use MyBB\Core\Database\Models\Conversation as ConversationModel;

class Conversation extends BasePresenter
{
	/** @var ConversationModel $wrappedObject */

	private $guard;

	/**
	 * @param ConversationModel $resource The conversation being wrapped by this presenter.
	 */
	public function __construct(ConversationModel $resource, Guard $guard)
	{
		$this->wrappedObject = $resource;
		$this->guard = $guard;
	}

	public function lastMessage()
	{
		if($this->wrappedObject->lastMessage instanceof ConversationMessage) {
			return $this->wrappedObject->lastMessage;
		}

		return app()->make('MyBB\Core\Presenters\ConversationMessage', [$this->wrappedObject->lastMessage]);
	}

	public function isUnread(User $user = null)
	{
		if ($user == null) {
			$user = $this->guard->user();
		}

		$participantData = $this->wrappedObject->participants()->find($user->id);

		if ($participantData->last_read == null) {
			return true;
		}

		if ($participantData->last_read < $this->wrappedObject->lastMessage->created_at) {
			return true;
		}

		return false;
	}

}
