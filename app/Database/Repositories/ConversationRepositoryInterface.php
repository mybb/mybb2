<?php
/**
 * Conversation repository contract.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Repositories;

use Illuminate\Support\Collection;
use MyBB\Core\Database\Models\Conversation;
use MyBB\Core\Database\Models\User;

interface ConversationRepositoryInterface
{
	/**
	 * Get all conversations.
	 *
	 * @return mixed
	 */
	public function all();

	/**
	 * Get a single conversation by ID.
	 *
	 * @param int $id The ID of the conversation.
	 *
	 * @return Conversation|null
	 */
	public function find($id = 0);

	/**
	 * @param User $user
	 *
	 * @return Collection
	 */
	public function getUnreadForUser(User $user);

	/**
	 * @param array $conversation
	 *
	 * @return Conversation
	 */
	public function create($conversation);

	/**
	 * @param Conversation $conversation
	 * @param User         $user
	 *
	 * @return void
	 */
	public function updateLastRead(Conversation $conversation, User $user);

	/**
	 * @param Conversation $conversation
	 * @param User         $user
	 *
	 * @return void
	 */
	public function leaveConversation(Conversation $conversation, User $user);

	/**
	 * @param Conversation $conversation
	 * @param User         $user
	 *
	 * @return void
	 */
	public function ignoreConversation(Conversation $conversation, User $user);
}
