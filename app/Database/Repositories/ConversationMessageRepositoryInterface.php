<?php
/**
 * Conversation repository contract.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Repositories;

use MyBB\Core\Database\Models\Conversation;

interface ConversationMessageRepositoryInterface
{
	/**
	 * Get all forums.
	 *
	 * @return mixed
	 */
	public function all();

	/**
	 * Get a single forum by ID.
	 *
	 * @param int $id The ID of the forum.
	 *
	 * @return mixed
	 */
	public function find($id = 0);

	public function getAllForConversation(Conversation $conversation);

	public function addMessageToConversation(Conversation $conversation, $details);
}
