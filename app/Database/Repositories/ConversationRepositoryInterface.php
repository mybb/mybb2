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
use MyBB\Core\Database\Models\User;
use MyBB\Core\Exceptions\ConversationAlreadyParticipantException;
use MyBB\Core\Exceptions\ConversationCantSendToSelfException;

interface ConversationRepositoryInterface
{
	/**
	 * Get all conversations.
	 *
	 * @return Collection
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
	public function create(array $conversation);

	/**
	 * @param Conversation $conversation
	 * @param array|int $participants
	 *
	 * @return void
	 *
	 * @throws ConversationCantSendToSelfException
	 * @throws ConversationAlreadyParticipantException
	 */
	public function addParticipants(Conversation $conversation, $participants);

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
