<?php
/**
 * Conversation repository contract.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Repositories;

use Illuminate\Database\Eloquent\Collection;
use MyBB\Core\Database\Models\Conversation;
use MyBB\Core\Database\Models\ConversationMessage;

interface ConversationMessageRepositoryInterface
{
	/**
	 * Get all conversation messages.
	 *
	 * @return Collection
	 */
	public function all();

	/**
	 * Get a single message by id
	 *
	 * @param int $id The ID of the message.
	 *
	 * @return ConversationMessage|null
	 */
	public function find($id = 0);

	/**
	 * @param Conversation $conversation
	 *
	 * @return Collection
	 */
	public function getAllForConversation(Conversation $conversation);

	/**
	 * @param Conversation $conversation
	 * @param array        $details
	 *
	 * @return ConversationMessage
	 */
	public function addMessageToConversation(Conversation $conversation, $details, $checkParticipants = true);

	/**
	 * @param Conversation $conversation
	 */
	public function deleteMessagesFromConversation(Conversation $conversation);
}
