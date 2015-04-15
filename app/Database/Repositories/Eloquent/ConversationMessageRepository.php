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
	/** @var ConversationMessage */
	protected $conversationMessageModel;

	/** @var PermissionChecker */
	private $permissionChecker;

	/**
	 * @param ConversationMessage $conversationMessageModel
	 * @param PermissionChecker   $permissionChecker
	 */
	public function __construct(
		ConversationMessage $conversationMessageModel,
		PermissionChecker $permissionChecker
	) {
		$this->conversationMessageModel = $conversationMessageModel;
		$this->permissionChecker = $permissionChecker;
	}

	/**
	 * {@inheritdoc}
	 */
	public function all()
	{
		return $this->conversationMessageModel->all();
	}

	/**
	 * {@inheritdoc}
	 */
	public function find($id = 0)
	{
		return $this->conversationMessageModel->with(['messages'])->find($id);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAllForConversation(Conversation $conversation)
	{
		return $this->conversationMessageModel->where('conversation_id', $conversation->id)->get();
	}

	/**
	 * {@inheritdoc}
	 */
	public function addMessageToConversation(Conversation $conversation, $details, $checkParticipants = true)
	{
		$message = $conversation->messages()->create($details);

		if ($message) {
			$conversation->update([
				'last_message_id' => $message->id
			]);

			if($checkParticipants) {
				$conversation->participants()->wherePivot('has_left', true)->update(['has_left' => false]);
			}
		}

		return $message;
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleteMessagesFromConversation(Conversation $conversation)
	{
		$this->conversationMessageModel->where('conversation_id', '=', $conversation->id)->delete();
	}
}
