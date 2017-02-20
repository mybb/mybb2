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
use MyBB\Core\Database\Models\Conversation;
use MyBB\Core\Database\Models\ConversationMessage;

interface ConversationMessageRepositoryInterface
{
    /**
     * Get all conversation messages.
     *
     * @return Collection
     */
    public function all() : Collection;

    /**
     * Get a single message by id
     *
     * @param int $id The ID of the message.
     *
     * @return ConversationMessage|null
     */
    public function find(int $id = 0);

    /**
     * @param Conversation $conversation
     *
     * @return Collection
     */
    public function getAllForConversation(Conversation $conversation) : Collection;

    /**
     * @param Conversation $conversation
     * @param array $details
     * @param bool $checkParticipants Whether or not participants who left the conversation but don't ignore it
     *                                        should be readded
     *
     * @return ConversationMessage
     */
    public function addMessageToConversation(Conversation $conversation, array $details, $checkParticipants = true) : ConversationMessage;

    /**
     * @param Conversation $conversation
     */
    public function deleteMessagesFromConversation(Conversation $conversation);
}
