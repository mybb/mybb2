<?php
/**
 * Forum repository implementation, using Eloquent ORM.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Repositories\Eloquent;

use MyBB\Core\Database\Models\Conversation;
use MyBB\Core\Database\Models\User;
use MyBB\Core\Database\Repositories\ConversationRepositoryInterface;
use MyBB\Core\Database\Repositories\ForumRepositoryInterface;
use MyBB\Core\Permissions\PermissionChecker;

class ConversationRepository implements ConversationRepositoryInterface
{
	protected $conversationModel;

	/**
	 * @var PermissionChecker
	 */
	private $permissionChecker;

	public function __construct(
		Conversation $conversationModel,
		PermissionChecker $permissionChecker
	) {
		$this->conversationModel = $conversationModel;
		$this->permissionChecker = $permissionChecker;
	}


	public function all()
	{
		return $this->conversationModel->all();
	}

	public function find($id = 0)
	{
		return $this->conversationModel->find($id);
	}

	public function getUnreadForUser(User $user)
	{
		// TODO: this is a big query, should probably be cached (at least for the request)
		return $this->conversationModel
			->join('conversation_user', function($join) use ($user) {
				$join->on('conversation_user.conversation_id', '=', 'conversations.id');
				$join->where('conversation_user.user_id', '=', $user->id);
			})
			->join('conversations_messages', 'conversations_messages.id', '=', 'conversations.last_message_id')
			->where('conversations_messages.created_at', '>', 'conversation_user.last_read')
			->orWhere('conversation_user.last_read', null)
			->get();
	}
}
