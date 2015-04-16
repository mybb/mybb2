<?php
/**
 * Controller to handle requests operating against single posts.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2014, MyBB Group
 * @license   http://www.mybb.com/about/license GNU LESSER GENERAL PUBLIC LICENSE
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\Http\Controllers;

use Breadcrumbs;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use MyBB\Core\Database\Models\Conversation;
use MyBB\Core\Database\Models\User;
use MyBB\Core\Database\Repositories\ConversationMessageRepositoryInterface;
use MyBB\Core\Database\Repositories\ConversationRepositoryInterface;
use MyBB\Core\Exceptions\ConversationAlreadyParticipantException;
use MyBB\Core\Exceptions\ConversationCantSendToSelfException;
use MyBB\Core\Exceptions\ConversationNotFoundException;
use MyBB\Core\Http\Requests\Conversations\CreateRequest;
use MyBB\Core\Http\Requests\Conversations\ParticipantRequest;
use MyBB\Core\Http\Requests\Conversations\ReplyRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ConversationsController extends Controller
{
	/** @var ConversationRepositoryInterface */
	private $conversationRepository;

	/** @var ConversationMessageRepositoryInterface */
	private $conversationMessageRepository;

	/** @var Guard */
	private $guard;

	/**
	 * @param ConversationRepositoryInterface        $conversationRepository
	 * @param ConversationMessageRepositoryInterface $conversationMessageRepository
	 * @param Guard                                  $guard
	 */
	public function __construct(
		ConversationRepositoryInterface $conversationRepository,
		ConversationMessageRepositoryInterface $conversationMessageRepository,
		Guard $guard
	) {
		$this->conversationRepository = $conversationRepository;
		$this->conversationMessageRepository = $conversationMessageRepository;
		$this->guard = $guard;

		$guard->user()->load('conversations');
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('conversation.index');
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function getCompose()
	{
		return view('conversation.compose');
	}

	/**
	 * @param CreateRequest $request
	 *
	 * @return $this|\Illuminate\Http\RedirectResponse
	 */
	public function postCompose(CreateRequest $request)
	{
		// TODO: Move this to CreateRequest?
		$participants = explode(',', $request->input('participants'));
		$participants = array_map('trim', $participants);

		$participants_id = array();
		foreach ($participants as $participant) {
			$user = User::where('name', $participant)->first();

			if (!$user) {
				throw new \RuntimeException('Invalid User');
			}

			$participants_id[] = $user->id;
		}

		try {
			$conversation = $this->conversationRepository->create([
				'title' => $request->input('title'),
				'message' => $request->input('message'),
				'participants' => $participants_id
			]);
		} catch (ConversationCantSendToSelfException $exception) {
			return redirect()->route('conversations.compose')->withInput()->withErrors([
				'participants' => $exception->getMessage()
			]);
		}

		if ($conversation) {
			return redirect()->route('conversations.read', ['id' => $conversation->id]);
		}

		return redirect()->route('conversations-compose')->withInput()->withErrors([
			'content' => 'error'
		]);
	}

	/**
	 * @param $id
	 *
	 * @return \Illuminate\View\View
	 */
	public function getRead($id)
	{
		$conversation = $this->conversationRepository->find($id);

		if (!$conversation) {
			throw new ConversationNotFoundException;
		}

		Breadcrumbs::setCurrentRoute('conversations.read', $conversation);

		$this->conversationRepository->updateLastRead($conversation, $this->guard->user());

		// Load the participants here as we're changing them above and we want to avoid caching issues
		$conversation->load('participants');

		$messages = $this->conversationMessageRepository->getAllForConversation($conversation);

		return view('conversation.show', compact('conversation', 'messages'));
	}

	/**
	 * @param              $id
	 * @param ReplyRequest $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function postReply($id, ReplyRequest $request)
	{
		$this->failedValidationRedirect = route('conversations.read', ['id' => $id]);

		/** @var Conversation $conversation */
		$conversation = $this->conversationRepository->find($id);

		if (!$conversation) {
			throw new ConversationNotFoundException;
		}

		$message = $this->conversationMessageRepository->addMessageToConversation($conversation, [
			'author_id' => $this->guard->user()->id,
			'message' => $request->input('message')
		]);

		if ($message) {
			return redirect()->route('conversations.read', ['id' => $conversation->id]);
		}

		return redirect()->route('conversations.read', ['id' => $conversation->id])->withInput()->withErrors([
			'content' => 'Error'
		]);
	}

	/**
	 * @param $id
	 *
	 * @return \Illuminate\View\View
	 */
	public function getLeave($id)
	{
		/** @var Conversation $conversation */
		$conversation = $this->conversationRepository->find($id);

		if (!$conversation) {
			throw new ConversationNotFoundException;
		}

		Breadcrumbs::setCurrentRoute('conversations.leave', $conversation);

		return view('conversation.leave', compact('conversation'));
	}

	/**
	 * @param         $id
	 * @param Request $request
	 *
	 * @return mixed
	 */
	public function postLeave($id, Request $request)
	{
		/** @var Conversation $conversation */
		$conversation = $this->conversationRepository->find($id);

		if (!$conversation) {
			throw new ConversationNotFoundException;
		}

		if ($request->input('leave') == 'leave') {
			$this->conversationRepository->leaveConversation($conversation, $this->guard->user());
		} else {
			$this->conversationRepository->ignoreConversation($conversation, $this->guard->user());
		}

		return redirect()->route('conversations.index')->withSuccess('Conversation left');
	}

	/**
	 * @param $id
	 *
	 * @return \Illuminate\View\View
	 */
	public function getNewParticipant($id)
	{
		/** @var Conversation $conversation */
		$conversation = $this->conversationRepository->find($id);

		if (!$conversation) {
			throw new ConversationNotFoundException;
		}

		Breadcrumbs::setCurrentRoute('conversations.newParticipant', $conversation);

		return view('conversation.new_participant', compact('conversation'));
	}

	/**
	 * @param                    $id
	 * @param ParticipantRequest $request
	 *
	 * @return mixed
	 */
	public function postNewParticipant($id, ParticipantRequest $request)
	{
		/** @var Conversation $conversation */
		$conversation = $this->conversationRepository->find($id);

		if (!$conversation) {
			throw new ConversationNotFoundException;
		}

		// TODO: Move this to a ParticipantRequest?
		$participants = explode(',', $request->input('participants'));
		$participants = array_map('trim', $participants);

		$participants_id = array();
		foreach ($participants as $participant) {
			$user = User::where('name', $participant)->first();

			if (!$user) {
				throw new \RuntimeException('Invalid User');
			}

			$participants_id[] = $user->id;
		}

		try {
			$this->conversationRepository->addParticipants($conversation, $participants_id);
		} catch (ConversationCantSendToSelfException $exception) {
			return redirect()->route('conversations.newParticipant',
				['id' => $conversation->id])->withInput()->withErrors([
				'participants' => $exception->getMessage()
			]);
		} catch (ConversationAlreadyParticipantException $exception) {
			return redirect()->route('conversations.newParticipant',
				['id' => $conversation->id])->withInput()->withErrors([
				'participants' => $exception->getMessage()
			]);
		}

		return redirect()->route('conversations.read', ['id' => $conversation->id])->withSuccess('Added participants');
	}
}
