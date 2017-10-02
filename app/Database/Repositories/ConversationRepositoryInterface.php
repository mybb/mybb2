<?php
/**
 * Conversation repository contract.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories;

use Illuminate\Database\Eloquent\Collection;
use MyBB\Core\Database\Models\{
    Conversation, User
};
use MyBB\Core\Exceptions\{
    ConversationAlreadyParticipantException, ConversationCantSendToSelfException
};

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
    public function find(int $id = 0);

    /**
     * @param User $user
     *
     * @return Collection
     */
    public function getForUser(User $user);

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
    public function create(array $conversation) : Conversation;

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
     * @param User $user
     *
     * @return void
     */
    public function updateLastRead(Conversation $conversation, User $user);

    /**
     * @param Conversation $conversation
     * @param User $user
     *
     * @return void
     */
    public function leaveConversation(Conversation $conversation, User $user);

    /**
     * @param Conversation $conversation
     * @param User $user
     *
     * @return void
     */
    public function ignoreConversation(Conversation $conversation, User $user);
}
