<?php
/**
 * Conversation message repository implementation, using Eloquent ORM.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories\Eloquent;

use MyBB\Core\Database\Models\Conversation;
use MyBB\Core\Database\Models\ConversationMessage;
use MyBB\Core\Database\Repositories\ConversationMessageRepositoryInterface;
use MyBB\Core\Database\Repositories\UserRepositoryInterface;
use MyBB\Parser\Parser;
use MyBB\Settings\Store;

class ConversationMessageRepository implements ConversationMessageRepositoryInterface
{
    /**
     * @var ConversationMessage
     */
    protected $conversationMessageModel;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var Parser
     */
    private $messageFormatter;

    /**
     * @var Store
     */
    private $settings;

    /**
     * @param ConversationMessage $conversationMessageModel
     * @param UserRepositoryInterface $userRepository
     * @param Parser $messageFormatter
     * @param Store $settings
     */
    public function __construct(
        ConversationMessage $conversationMessageModel,
        UserRepositoryInterface $userRepository,
        Parser $messageFormatter,
        Store $settings
    ) {
        $this->conversationMessageModel = $conversationMessageModel;
        $this->userRepository = $userRepository;
        $this->messageFormatter = $messageFormatter;
        $this->settings = $settings;
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
        return $this->conversationMessageModel->where('conversation_id', $conversation->id)
            ->orderBy('created_at', $this->settings->get('conversations.message_order', 'desc'))
            ->paginate($this->settings->get('user.posts_per_page', 10));
    }

    /**
     * {@inheritdoc}
     */
    public function addMessageToConversation(Conversation $conversation, array $details, $checkParticipants = true)
    {
        $details['message_parsed'] = $this->messageFormatter->parse($details['message'], [
            MessageFormatter::ME_USERNAME => $this->userRepository->find($details['author_id'])->name,
        ]); // TODO: Parser options...

        $message = $conversation->messages()->create($details);

        if ($message) {
            $conversation->update([
                'last_message_id' => $message->id,
            ]);

            if ($checkParticipants) {
                $users = $conversation->participants()->wherePivot(
                    'has_left',
                    true
                )->get(['user_id'])->lists('user_id');
                $conversation->participants()->newPivotStatement()->where('conversation_id', $conversation->id)
                    ->whereIn('user_id', $users)->update(['has_left' => false]);

                // This would be the better query but only MySQL wants to run it, PgSQL and SQLite don't like it
                // $conversation->participants()->wherePivot('has_left', true)->update(['has_left' => false]);
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
