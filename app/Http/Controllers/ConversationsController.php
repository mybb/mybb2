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
use MyBB\Core\Database\Repositories\ConversationRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ConversationsController extends Controller
{
	private $conversationRepository;

	/**
	 * @param ConversationRepositoryInterface $conversationRepository
	 * @param Guard $guard
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

	}

	public function postCompose()
	{

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
