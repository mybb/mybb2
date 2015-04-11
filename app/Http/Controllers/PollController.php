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
use MyBB\Core\Database\Repositories\ForumRepositoryInterface;
use MyBB\Core\Database\Repositories\PollRepositoryInterface;
use MyBB\Core\Database\Repositories\PollVoteRepositoryInterface;
use MyBB\Core\Database\Repositories\TopicRepositoryInterface;
use MyBB\Core\Http\Requests\Poll\VoteRequest;
use MyBB\Core\Presenters\Poll as PollPresenter;
use MyBB\Core\Http\Requests\Poll\CreateRequest;
use MyBB\Settings\Store;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PollController extends Controller
{
	/** @var TopicRepositoryInterface $topicRepository */
	private $topicRepository;
	/** @var PollRepositoryInterface $postRepository */
	private $pollRepository;
	/** @var PollVoteRepositoryInterface $postRepository */
	private $pollVoteRepository;
	/** @var ForumRepositoryInterface $forumRepository */
	private $forumRepository;
	/** @var Guard $guard */
	private $guard;

	/**
	 * @param TopicRepositoryInterface    $topicRepository Topic repository instance, used to fetch topic details.
	 * @param PollRepositoryInterface     $pollRepository Poll repository instance, used to fetch poll details.
	 * @param PollVoteRepositoryInterface $pollVoteRepository PollVote repository instance, used to fetch poll vote details.
	 * @param ForumRepositoryInterface    $forumRepository Forum repository interface, used to fetch forum details.
	 * @param Guard                       $guard Guard implementation
	 * @param Request                     $request Request implementation
	 */
	public function __construct(
		TopicRepositoryInterface $topicRepository,
		PollRepositoryInterface $pollRepository,
		PollVoteRepositoryInterface $pollVoteRepository,
		ForumRepositoryInterface $forumRepository,
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

	/**
	 * @param  string $topicSlug
	 * @param  int    $topicId
	 * @return \Illuminate\View\View
	 */
	public function show($topicSlug, $topicId)
	{
		$topic = $this->topicRepository->find($topicId);
		if (!$topic) {
			throw new NotFoundHttpException(trans('errors.topic_not_found'));
		}

		if (!$topic->has_poll) {
			throw new NotFoundHttpException(trans('errors.poll_not_found'));
		}

		$poll = $topic->poll;
		$pollPresenter = app()->make('MyBB\Core\Presenters\Poll', [$poll]);

		Breadcrumbs::setCurrentRoute('polls.show', $topic);

		$options = $pollPresenter->options();

		if ($poll->is_public) {
			$allVotes = $this->pollVoteRepository->allForPoll($poll);
			foreach ($allVotes as $vote) {
				if ($vote->user_id && $vote['vote']) {
					$votes = explode(',', $vote['vote']);
					foreach ($votes as $theVote) {
						if (!isset($options[$theVote - 1]['users'])) {
							$options[$theVote - 1]['users'] = [];
						}
						$options[$theVote - 1]['users'][] = &$vote->author;
					}
				}
			}
		}

		return view('polls.show', compact('topic', 'options', 'poll', 'myVote'));
	}

	/**
	 * @param string $slug
	 * @param int    $id
	 * @return \Illuminate\View\View
	 */
	public function create($slug, $id)
	{
		$topic = $this->topicRepository->find($id);

		if (!$topic) {
			throw new NotFoundHttpException(trans('errors.topic_not_found'));
		}

		Breadcrumbs::setCurrentRoute('polls.create', $topic);

		return view('polls.create', compact('topic'));
	}

	/**
	 * @param  string       $slug
	 * @param  int          $id
	 * @param CreateRequest $createRequest
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function postCreate($slug, $id, CreateRequest $createRequest)
	{
		$topic = $this->topicRepository->find($id);

		if (!$topic) {
			throw new NotFoundHttpException(trans('errors.topic_not_found'));
		}
		Breadcrumbs::setCurrentRoute('polls.create', $topic);

		$poll = [
			'topic_id' => $id,
			'question' => $createRequest->input('question'),
			'num_options' => count($createRequest->options()),
			'options' => $createRequest->options(),
			'is_closed' => false,
			'is_multiple' => (bool)$createRequest->input('is_multiple'),
			'is_public' => (bool)$createRequest->input('is_public'),
			'end_at' => null,
			'max_options' => $createRequest->input('maxoptions')
		];
		if ($createRequest->input('endAt')) {
			$poll['end_at'] = new \DateTime($createRequest->input('endAt'));
		}
		$poll = $this->pollRepository->create($poll);

		if ($poll) {
			$this->topicRepository->setHasPoll($topic, true);

			return redirect()->route('topics.show', ['slug' => $topic->slug, 'id' => $topic->id]);
		}

		return redirect()->route('polls.create')->withInput()->withError(['error' => trans('error.error_creating_poll')]);
	}

	/**
	 * @param string  $topicSlug
	 * @param int     $topicId
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws \Exception
	 */
	public function vote($topicSlug, $topicId)
	{
		$topic = $this->topicRepository->find($topicId);
		if (!$topic) {
			throw new NotFoundHttpException(trans('errors.topic_not_found'));
		}

		if (!$topic->has_poll) {
			throw new NotFoundHttpException(trans('errors.poll_not_found'));
		}

		$poll = $topic->poll;
		$pollPresenter = app()->make('MyBB\Core\Presenters\Poll', [$poll]);

        $voteRequest = app()->make('MyBB\Core\Http\Requests\Poll\VoteRequest', [$pollPresenter]);

		if ($pollPresenter->is_closed) {
			throw new \Exception(trans('errors.poll_is_closed'));
		}

		// Is the user already voted?
		if ($this->guard->check()) {
			$myVote = $this->pollVoteRepository->findForUserPoll($this->guard->user(), $poll);
			if ($myVote) {
				// Error
				throw new \Exception(trans('errors.you_already_vote'));
			}
		}

		$votes = $voteRequest->input('option');
		$options = $pollPresenter->options();

		if ($poll->is_multiple) {
			$votes = array_unique($votes, SORT_NUMERIC);

			// Increment num votes of options that the user voted
			foreach ($votes as $vote) {
				$options[$vote - 1]['votes']++;
			}
			$votes = implode(',', $votes);
		} else {
			// Increment num votes of the option that the user voted
			$options[$votes - 1]['votes']++;
		}

		$vote = $this->pollVoteRepository->create([
			'poll_id' => $poll->id,
			'vote' => $votes
		]);

		if ($vote) {
            $this->pollRepository->editPoll($poll, ['options' => $options]);
		}

		return redirect()->route('polls.show', [$topicSlug, $topicId]);
	}


	/**
	 * @param string $topicSlug
	 * @param int    $topicId
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws \Exception
	 */
	public function undo($topicSlug, $topicId)
	{
		$topic = $this->topicRepository->find($topicId);
		if (!$topic) {
			throw new NotFoundHttpException(trans('errors.topic_not_found'));
		}

		if (!$topic->has_poll) {
			throw new NotFoundHttpException(trans('errors.poll_not_found'));
		}

		$poll = $topic->poll;
		$pollPresenter = app()->make('MyBB\Core\Presenters\Poll', [$poll]);

		if (!$this->guard->check()) {
			throw new \Exception(trans('errors.poll_guest_undo'));
		}

		if ($pollPresenter->is_closed) {
			throw new \Exception(trans('errors.poll_is_closed'));
		}

		$vote = $this->pollVoteRepository->findForUserPoll($this->guard->user(), $poll);
		if (!$vote) {
			// Error
			throw new \Exception(trans('errors.poll_nothing_to_undo'));
		}

		$votes = explode(',', $vote->vote);

		$options = $pollPresenter->options();

		foreach ($votes as $option) {
			if (is_numeric($option) && 0 < $option && $option <= $pollPresenter->num_options()) {
				$options[$option - 1]['votes']--;
			}
		}

		$poll->update(['options' => $options]);
		$vote->delete();

		return redirect()->route('polls.show', [$topicSlug, $topicId]);
	}

	/**
	 * @param string $topicSlug
	 * @param int    $topicId
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function remove($topicSlug, $topicId)
	{
		$topic = $this->topicRepository->find($topicId);
		if (!$topic) {
			throw new NotFoundHttpException(trans('errors.topic_not_found'));
		}

		if (!$topic->has_poll) {
			throw new NotFoundHttpException(trans('errors.poll_not_found'));
		}

		$poll = $topic->poll;

		$this->pollRepository->remove($poll);

		$topic->has_poll = false;
		$topic->save();

		return redirect()->route('topics.show', [$topicSlug, $topicId]);
	}

	/**
	 * @param string $topicSlug
	 * @param int    $topicId
	 * @return \Illuminate\View\View
	 */
	public function edit($topicSlug, $topicId)
	{
		$topic = $this->topicRepository->find($topicId);
		if (!$topic) {
			throw new NotFoundHttpException(trans('errors.topic_not_found'));
		}

		if (!$topic->has_poll) {
			throw new NotFoundHttpException(trans('errors.poll_not_found'));
		}

		$poll = $topic->poll;

		Breadcrumbs::setCurrentRoute('polls.edit', $topic);

		return view('polls.edit', compact('topic', 'poll'));
	}

	/**
	 * @param string        $topicSlug
	 * @param int           $topicId
	 * @param CreateRequest $createRequest
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function postEdit($topicSlug, $topicId, CreateRequest $createRequest)
	{
		$topic = $this->topicRepository->find($topicId);
		if (!$topic) {
			throw new NotFoundHttpException(trans('errors.topic_not_found'));
		}

		if (!$topic->has_poll) {
			throw new NotFoundHttpException(trans('errors.poll_not_found'));
		}

		$poll = $topic->poll;
		$pollPresenter = app()->make('MyBB\Core\Presenters\Poll', [$poll]);


		$options = [];
		$i = 0;
		foreach ($createRequest->input('option') as $option) {
			if ($option && is_scalar($option)) {
				$options[] = [
					'option' => $option,
					'votes' => 0
				];
				if (isset($pollPresenter->options[$i]['votes'])) {
					$options[$i]['votes'] = $pollPresenter->options[$i]['votes'];
				}
				++$i;
			}
		}

		$pollDetails = [
			'question' => $createRequest->input('question'),
			'num_options' => count($options),
			'options' => $options,
			'is_closed' => (bool)$createRequest->input('is_closed'),
			'is_multiple' => (bool)$createRequest->input('is_multiple'),
			'is_public' => (bool)$createRequest->input('is_public'),
			'max_options' => $createRequest->input('maxoptions')
		];
		if ($createRequest->input('endAt')) {
			$poll['end_at'] = new \DateTime($createRequest->input('endAt'));
		}

		$poll->update($pollDetails);

		if ($poll) {
			return redirect()->route('topics.show', ['slug' => $topic->slug, 'id' => $topic->id]);
		}

		return redirect()->route('polls.edit')->withInput()->withError(['error' => trans('error.error_editing_poll')]);
	}
}
