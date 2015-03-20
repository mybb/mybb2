<?php
/**
 * Poll Controller.
 *
 * Used to view, create, delete and update polls.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Http\Controllers;

use Breadcrumbs;
use Illuminate\Auth\Guard;
use Illuminate\Http\Request;
use MyBB\Core\Database\Repositories\IForumRepository;
use MyBB\Core\Database\Repositories\IPollRepository;
use MyBB\Core\Database\Repositories\IPollVoteRepository;
use MyBB\Core\Database\Repositories\ITopicRepository;
use MyBB\Core\Http\Requests\Poll\CreateRequest;
use MyBB\Settings\Store;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PollController extends Controller
{
	/** @var ITopicRepository $topicRepository */
	private $topicRepository;
	/** @var IPollRepository $postRepository */
	private $pollRepository;
	/** @var IPollVoteRepository $postRepository */
	private $pollVoteRepository;
	/** @var IForumRepository $forumRepository */
	private $forumRepository;
	/** @var Guard $guard */
	private $guard;

	/**
	 * @param ITopicRepository $topicRepository Topic repository instance, used to fetch topic details.
	 * @param IPollRepository  $pollRepository  Poll repository instance, used to fetch poll details.
	 * @param IPollVoteRepository  $pollVoteRepository  PollVote repository instance, used to fetch poll vote details.
	 * @param IForumRepository $forumRepository Forum repository interface, used to fetch forum details.
	 * @param Guard            $guard           Guard implementation
	 * @param Request          $request         Request implementation
	 */
	public function __construct(
		ITopicRepository $topicRepository,
		IPollRepository $pollRepository,
		IPollVoteRepository $pollVoteRepository,
		IForumRepository $forumRepository,
		Guard $guard,
		Request $request
	) {
		parent::__construct($guard, $request);

		$this->topicRepository = $topicRepository;
		$this->pollRepository = $pollRepository;
		$this->pollVoteRepository = $pollVoteRepository;
		$this->forumRepository = $forumRepository;
		$this->guard = $guard;
	}

	public function create($slug = '', $id = 0)
	{
		$topic = $this->topicRepository->find($id);

		if(!$topic)
		{
			throw new NotFoundHttpException(trans('errors.topic_not_found'));
		}

		Breadcrumbs::setCurrentRoute('polls.create', $topic);// TODO

		return view('polls.create', compact('topic'));
	}

	public function postCreate($slug = '', $id = 0, CreateRequest $createRequest)
	{
		$topic = $this->topicRepository->find($id);

		if(!$topic)
		{
			throw new NotFoundHttpException(trans('errors.topic_not_found'));
		}
		Breadcrumbs::setCurrentRoute('polls.create', $topic);// TODO

		$options = [];
		foreach($createRequest->input('option') as $option)
		{
			if($option && is_scalar($option)) {
				$options[] = [
					'option' => $option,
					'votes' => 0
				];
			}
		}

		$poll = $this->pollRepository->create([
			'topic_id' => $id,
			'question' => $createRequest->input('question'),
			'num_options' => count($options),
			'options' => json_encode($options),
			'is_closed' => false,
			'is_multiple' => (bool)$createRequest->input('is_multiple'),
			'is_public' => (bool)$createRequest->input('is_public'),
			'end_at' => new \DateTime('+'.$createRequest->input('timeout').' days'),
			'max_options' => $createRequest->input('maxoptions')
		]);

		if($poll)
		{
			return redirect()->route('topics.show', ['slug' => $topic->slug, 'id' => $topic->id]);
		}

		return new \Exception(trans('errors.error_creating_poll')); // TODO: Redirect back with error...
	}
}
