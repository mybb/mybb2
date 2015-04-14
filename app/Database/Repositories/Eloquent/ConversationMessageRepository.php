<?php
/**
 * Forum repository implementation, using Eloquent ORM.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Repositories\Eloquent;

use MyBB\Core\Database\Models\ConversationMessage;
use MyBB\Core\Database\Models\Conversation;
use MyBB\Core\Database\Repositories\ConversationMessageRepositoryInterface;
use MyBB\Core\Permissions\PermissionChecker;

class ConversationMessageRepository implements ConversationMessageRepositoryInterface
{
	protected $conversationMessageModel;

	/**
	 * @var PermissionChecker
	 */
	private $permissionChecker;

	public function __construct(
		ConversationMessage $conversationMessageModel,
		PermissionChecker $permissionChecker
	) {
		$this->conversationMessageModel = $conversationMessageModel;
		$this->permissionChecker = $permissionChecker;
	}


	public function all()
	{
		return $this->conversationMessageModel->all();
	}

	public function find($id = 0)
	{
		return $this->conversationMessageModel->with(['messages'])->find($id);
	}

	public function getAllForConversation(Conversation $conversation)
	{
		return $this->conversationMessageModel->where('conversation_id', $conversation->id)->get();
	}

	public function addMessageToConversation(Conversation $conversation, $details)
	{
		$message = $conversation->messages()->create($details);

		if($message) {
			$conversation->update([
				'last_message_id' => $message->id
			]);
		}

		return $message;
	}

	public function deleteMessagesFromConversation(Conversation $conversation)
	{
		$this->conversationMessageModel->where('conversation_id', '=', $conversation->id)->delete();
	}
}
