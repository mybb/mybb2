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
		return $this->conversationModel->with(['messages'])->find($id);
	}

	public function getUnreadForUser(User $user)
	{
		return $this->conversationModel->where('participants.user_id', $user->id)->get();
	}
}
