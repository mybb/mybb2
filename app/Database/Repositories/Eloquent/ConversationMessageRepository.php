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
use MyBB\Core\Database\Models\ConversationMessage;
use MyBB\Core\Database\Repositories\ConversationMessageRepositoryInterface;
use MyBB\Core\Database\Repositories\UserRepositoryInterface;
use MyBB\Parser\MessageFormatter;

class ConversationMessageRepository implements ConversationMessageRepositoryInterface
{
	/** @var ConversationMessage */
	protected $conversationMessageModel;

	/** @var UserRepositoryInterface */
	private $userRepository;

	/** @var MessageFormatter */
	private $messageFormatter;

	/**
	 * @param ConversationMessage     $conversationMessageModel
	 * @param UserRepositoryInterface $userRepository
	 * @param MessageFormatter        $messageFormatter
	 */
	public function __construct(
		ConversationMessage $conversationMessageModel,
		UserRepositoryInterface $userRepository,
		MessageFormatter $messageFormatter
	) {
		$this->conversationMessageModel = $conversationMessageModel;
		$this->userRepository = $userRepository;
		$this->messageFormatter = $messageFormatter;
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
	public function addMessageToConversation(Conversation $conversation, array $details, $checkParticipants = true)
	{
		$details['message_parsed'] = $this->messageFormatter->parse($details['message'], [
			MessageFormatter::ME_USERNAME => $this->userRepository->find($details['author_id'])->name,
		]); // TODO: Parser options...

		$message = $conversation->messages()->create($details);

		if ($message) {
			$conversation->update([
				'last_message_id' => $message->id
			]);

			if ($checkParticipants) {
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
