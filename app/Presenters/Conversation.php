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
	private $dbManager;

	/**
	 * @param ConversationModel $resource The conversation being wrapped by this presenter.
	 */
	public function __construct(ConversationModel $resource, Guard $guard, DatabaseManager $dbManager)
	{
		$this->wrappedObject = $resource;
		$this->guard = $guard;
		$this->dbManager = $dbManager;
	}

	public function isUnread(User $user = null)
	{
		if ($user == null) {
			$user = $this->guard->user();
		}

		// TODO: Check whether it's possible to get one pivot data via the model instead of all (avoid this query which shouldn't be here)
		$participantData = $this->dbManager->table('conversation_user')
			->where('conversation_id', $this->wrappedObject->id)
			->where('user_id', $user->id)
			->first();

		if ($participantData->last_read == null) {
			return true;
		}

		if ($participantData->last_read < $this->wrappedObject->lastMessage->created_at) {
			return true;
		}

		return false;
	}

}
