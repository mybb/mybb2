<?php
/**
 * Controller to handle requests operating against single posts.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2015, MyBB Group
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\Http\Controllers;

use Breadcrumbs;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;
use MyBB\Core\Database\Models\{
    Conversation, ConversationMessage
};
use MyBB\Core\Database\Repositories\{
    ConversationMessageRepositoryInterface, ConversationRepositoryInterface
};
use MyBB\Core\Exceptions\{
    ConversationAlreadyParticipantException, ConversationCantSendToSelfException, ConversationNotFoundException
};
use MyBB\Core\Http\Requests\Conversations\{
    CreateRequest, ParticipantRequest, ReplyRequest
};
use MyBB\Parser\Parser;
use MyBB\Settings\Store;

class ConversationsController extends AbstractController
{
    /**
     * @var ConversationRepositoryInterface
     */
    private $conversationRepository;

    /**
     * @var ConversationMessageRepositoryInterface
     */
    private $conversationMessageRepository;

    /**
     * @var Guard
     */
    private $guard;

    /**
     * @param ConversationRepositoryInterface $conversationRepository
     * @param ConversationMessageRepositoryInterface $conversationMessageRepository
     * @param Guard $guard
     */
    public function __construct(
        ConversationRepositoryInterface $conversationRepository,
        ConversationMessageRepositoryInterface $conversationMessageRepository,
        Guard $guard
    ) {
        $this->conversationRepository = $conversationRepository;
        $this->conversationMessageRepository = $conversationMessageRepository;
        $this->guard = $guard;
    }

    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $conversations = $this->conversationRepository->getForUser($this->guard->user());

        return view('conversation.index', compact('conversations'));
    }

    /**
     * @param Request $request
     * @param Parser $parser
     *
     * @return \Illuminate\View\View
     */
    public function getCompose(Request $request, Parser $parser)
    {
        $preview = null;
        if ($request->has('message')) {
            $preview = new ConversationMessage([
                'author_id'      => $this->guard->user()->id,
                'message'        => $request->get('message'),
                'message_parsed' => $parser->parse($request->get('message'), [
                    'username' => $this->guard->user()->name,
                ]),
                'created_at'     => new \DateTime(),
            ]);
        }

        return view('conversation.compose', compact('preview'));
    }

    /**
     * @param CreateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCompose(CreateRequest $request)
    {
        try {
            $conversation = $this->conversationRepository->create([
                'title'        => $request->input('title'),
                'message'      => $request->input('message'),
                'participants' => $request->getUseridArray('participants'),
            ]);
        } catch (ConversationCantSendToSelfException $exception) {
            return redirect()->route('conversations.compose')->withInput()->withErrors([
                'participants' => $exception->getMessage(),
            ]);
        }

        if ($conversation) {
            return redirect()->route('conversations.read', ['id' => $conversation->id]);
        }

        return redirect()->route('conversations.compose')->withInput()->withErrors([
            'content' => 'error',
        ]);
    }

    /**
     * @param int $id
     * @param Request $request
     * @param Parser $parser
     *
     * @return \Illuminate\View\View
     */
    public function getRead(int $id, Request $request, Parser $parser)
    {
        $conversation = $this->conversationRepository->find($id);

        if (!$conversation || !$conversation->participants->contains($this->guard->user())) {
            throw new ConversationNotFoundException;
        }

        Breadcrumbs::setCurrentRoute('conversations.read', $conversation);

        $this->conversationRepository->updateLastRead($conversation, $this->guard->user());

        // Load the participants here as we're changing them above and we want to avoid caching issues
        $conversation->load('participants');

        $messages = $this->conversationMessageRepository->getAllForConversation($conversation);

        $preview = null;
        if ($request->has('message')) {
            $preview = new ConversationMessage([
                'author_id'      => $this->guard->user()->id,
                'message'        => $request->get('message'),
                'message_parsed' => $parser->parse($request->get('message'), [
                    'username' => $this->guard->user()->name,
                ]),
                'created_at'     => new \DateTime(),
            ]);
        }

        return view('conversation.show', compact('conversation', 'messages', 'preview'));
    }

    /**
     * @param int $id
     * @param ReplyRequest $request
     * @param Store $settings
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postReply(int $id, ReplyRequest $request, Store $settings)
    {
        $this->failedValidationRedirect = route('conversations.read', ['id' => $id]);

        /** @var Conversation $conversation */
        $conversation = $this->conversationRepository->find($id);

        if (!$conversation || !$conversation->participants->contains($this->guard->user())) {
            throw new ConversationNotFoundException;
        }

        $message = $this->conversationMessageRepository->addMessageToConversation($conversation, [
            'author_id' => $this->guard->user()->id,
            'message'   => $request->input('message'),
        ]);

        if ($message) {
            $page = 1;

            if ($settings->get('conversations.message_order', 'desc') == 'asc') {
                $page = (int)($conversation->messages->count() / $settings->get('user.posts_per_page', 10)) + 1;
            }

            return redirect()->route('conversations.read', ['id' => $conversation->id, 'page' => $page]);
        }

        return redirect()->route('conversations.read', ['id' => $conversation->id])->withInput()->withErrors([
            'content' => 'Error',
        ]);
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function getLeave(int $id)
    {
        /** @var Conversation $conversation */
        $conversation = $this->conversationRepository->find($id);

        if (!$conversation || !$conversation->participants->contains($this->guard->user())) {
            throw new ConversationNotFoundException;
        }

        Breadcrumbs::setCurrentRoute('conversations.leave', $conversation);

        return view('conversation.leave', compact('conversation'));
    }

    /**
     * @param int $id
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postLeave(int $id, Request $request)
    {
        /** @var Conversation $conversation */
        $conversation = $this->conversationRepository->find($id);

        if (!$conversation || !$conversation->participants->contains($this->guard->user())) {
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
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function getNewParticipant(int $id)
    {
        /** @var Conversation $conversation */
        $conversation = $this->conversationRepository->find($id);

        if (!$conversation || !$conversation->participants->contains($this->guard->user())) {
            throw new ConversationNotFoundException;
        }

        Breadcrumbs::setCurrentRoute('conversations.newParticipant', $conversation);

        return view('conversation.new_participant', compact('conversation'));
    }

    /**
     * @param int $id
     * @param ParticipantRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postNewParticipant(int $id, ParticipantRequest $request)
    {
        /** @var Conversation $conversation */
        $conversation = $this->conversationRepository->find($id);

        if (!$conversation || !$conversation->participants->contains($this->guard->user())) {
            throw new ConversationNotFoundException;
        }

        try {
            $this->conversationRepository->addParticipants($conversation, $request->getUseridArray('participants'));
        } catch (ConversationCantSendToSelfException $exception) {
            return redirect()->route(
                'conversations.newParticipant',
                ['id' => $conversation->id]
            )->withInput()->withErrors([
                'participants' => $exception->getMessage(),
            ]);
        } catch (ConversationAlreadyParticipantException $exception) {
            return redirect()->route(
                'conversations.newParticipant',
                ['id' => $conversation->id]
            )->withInput()->withErrors([
                'participants' => $exception->getMessage(),
            ]);
        }

        return redirect()->route('conversations.read', ['id' => $conversation->id])->withSuccess('Added participants');
    }
}
