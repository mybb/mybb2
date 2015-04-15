<?php
/**
 * Forum repository implementation, using Eloquent ORM.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Repositories\Eloquent;

use Illuminate\Database\DatabaseManager;
use Illuminate\Auth\Guard;
use Illuminate\Support\Collection;
use MyBB\Core\Database\Models\Conversation;
use MyBB\Core\Database\Models\User;
use MyBB\Core\Database\Repositories\ConversationMessageRepositoryInterface;
use MyBB\Core\Database\Repositories\ConversationRepositoryInterface;
use MyBB\Core\Permissions\PermissionChecker;

class ConversationRepository implements ConversationRepositoryInterface
{
	/** @var Conversation */
	protected $conversationModel;

	/** @var PermissionChecker */
	private $permissionChecker;

	/** @var DatabaseManager */
	private $dbManager;

	/** @var ConversationMessageRepositoryInterface */
	private $conversationMessageRepository;

	/** @var Guard */
	private $guard;

	/**
	 * @param Conversation                           $conversationModel
	 * @param PermissionChecker                      $permissionChecker
	 * @param DatabaseManager                        $dbManager
	 * @param ConversationMessageRepositoryInterface $conversationMessageRepository
	 * @param Guard                                  $guard
	 */
	public function __construct(
		Conversation $conversationModel,
		PermissionChecker $permissionChecker,
		DatabaseManager $dbManager,
		ConversationMessageRepositoryInterface $conversationMessageRepository,
		Guard $guard
	) {
		$this->conversationModel = $conversationModel;
		$this->permissionChecker = $permissionChecker;
		$this->dbManager = $dbManager;
		$this->conversationMessageRepository = $conversationMessageRepository;
		$this->guard = $guard;
	}

	/**
	 * {@inheritdoc}
	 */
	public function all()
	{
		return $this->conversationModel->all();
	}

	/**
	 * {@inheritdoc}
	 */
	public function find($id = 0)
	{
		return $this->conversationModel->find($id);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getUnreadForUser(User $user)
	{
		// TODO: this is a big query, should probably be cached (at least for the request)
		return $this->conversationModel
			->join('conversation_user', function ($join) use ($user) {
				$join->on('conversation_user.conversation_id', '=', 'conversations.id');
				$join->where('conversation_user.user_id', '=', $user->id);
			})
			->join('conversations_messages', 'conversations_messages.id', '=', 'conversations.last_message_id')
			->where('conversations_messages.created_at', '>', 'conversation_user.last_read')
			->orWhere('conversation_user.last_read', null)
			->orderBy('conversations_messages.created_at', 'desc')
			->get();
	}

	/**
	 * {@inheritdoc}
	 */
	public function updateLastRead(Conversation $conversation, User $user)
	{
		$conversation->participants()->updateExistingPivot($user->id, ['last_read' => new \DateTime()]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function leaveConversation(Conversation $conversation, User $user)
	{
		$conversation->participants()->updateExistingPivot($user->id, ['has_left' => true]);

		$this->checkForDeletion($conversation);
	}

	/**
	 * {@inheritdoc}
	 */
	public function ignoreConversation(Conversation $conversation, User $user)
	{
		$conversation->participants()->updateExistingPivot($user->id, ['ignores' => true]);

		$this->checkForDeletion($conversation);
	}

	/**
	 * {@inheritdoc}
	 */
	public function create($details)
	{
		$conversation = null;

		$this->dbManager->transaction(function () use ($details, &$conversation) {
			$conversation = $this->conversationModel->create([
				'title' => $details['title']
			]);

			$message = $this->conversationMessageRepository->addMessageToConversation($conversation, [
				'author_id' => $this->guard->user()->id,
				'message' => $details['message']
			], false);

			// First add the author of this message - if he answered it he also read the conversation
			$conversation->participants()->attach($this->guard->user()->id, ['last_read' => new \DateTime()]);

			// And now add all other participants
			$conversation->participants()->attach($details['participants']);

			$conversation->update([
				'last_message_id' => $message->id
			]);
		});

		return $conversation;
	}

	/**
	 * @param Conversation $conversation
	 */
	private function checkForDeletion(Conversation $conversation)
	{
		$participants = $conversation->participants;

		/** @var Collection $activeParticipants */
		$activeParticipants = $participants->whereLoose('pivot.has_left', false)->whereLoose('pivot.ignores', false);

		if ($activeParticipants->count() == 0) {
			// All participants either ignore or left this conversation so delete everything related to it

			$conversation->update([
				'last_message_id' => null
			]);

			// Calling sync with an empty array will delete all records
			$conversation->participants()->sync([]);

			$this->conversationMessageRepository->deleteMessagesFromConversation($conversation);

			$conversation->delete();
		}
	}
}
