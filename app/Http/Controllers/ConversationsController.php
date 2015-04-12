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

use Illuminate\Contracts\Auth\Guard;
use MyBB\Core\Database\Models\User;
use MyBB\Core\Database\Repositories\ConversationRepositoryInterface;
use MyBB\Core\Http\Requests\Conversations\CreateRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ConversationsController extends Controller
{
	private $conversationRepository;

	/**
	 * @param ConversationRepositoryInterface $conversationRepository
	 * @param Guard                           $guard
	 */
	public function __construct(ConversationRepositoryInterface $conversationRepository, Guard $guard)
	{
		$this->conversationRepository = $conversationRepository;
		$guard->user()->load('conversations');
	}

	public function index()
	{
		return view('conversation.index');
	}

	public function getCompose()
	{
		return view('conversation.compose');
	}

	public function postCompose(CreateRequest $request)
	{
		// TODO: Move this to CreateRequest?
		$participants = explode(',', $request->input('participants'));
		$participants = array_map('trim', $participants);

		$participants_id = array();
		foreach($participants as $participant) {
			$user = User::where('name', $participant)->first();

			if(!$user) {
				throw new \RuntimeException('Invalid User');
			}

			$participants_id[] = $user->id;
		}

		$conversation = $this->conversationRepository->create([
			'title' => $request->input('title'),
			'message' => $request->input('message'),
			'participants' => $participants_id
		]);

		if ($conversation) {
			return redirect()->route('conversations.read', ['id' => $conversation->id]);
		}

		return redirect()->route('conversations-compose')->withInput()->withErrors([
			'content' => 'error'
		]);
	}

	public function getRead($id)
	{

	}

	public function postReply($id)
	{

	}

	public function postNewRecipient($id)
	{

	}
}
