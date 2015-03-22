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
use MyBB\Core\Presenters\Poll as PollPresenter;
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

	public function show($topicSlug = null, $topicId = 0, $id = 0) {
		$topic = $this->topicRepository->find($topicId);
		if(!$topic) {
			throw new NotFoundHttpException(trans('errors.topic_not_found'));
		}

		$poll = $this->pollRepository->find($id);
		$pollPresenter = new PollPresenter($poll);

		if(!$poll || $poll->topic_id != $topic->id) {
			throw new NotFoundHttpException(trans('errors.poll_not_found'));
		}

		$myVote = $this->pollVoteRepository->findForUserPoll($this->guard->user(), $poll);
		$options = $pollPresenter->options();
		if($myVote) {
			$votes = explode(',', $myVote->vote);
			foreach($votes as $vote) {
				$options[$vote-1]->voted = true;
			}
		}

		if($poll->is_public) {
			$allVotes = $this->pollVoteRepository->allForPoll($poll);
			foreach($allVotes as $vote) {
				if($vote->user_id && $vote->vote) {
					$votes = explode(',', $vote->vote);
					foreach($votes as $theVote) {
						if(!isset($options[$theVote-1]->users)){
							$options[$theVote-1]->users = [];
						}
						$options[$theVote-1]->users[] = &$vote->author;
					}
				}
			}
		}

		return view('polls.show', compact('topic', 'options', 'poll', 'myVote'));
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

		$poll = [
			'topic_id' => $id,
			'question' => $createRequest->input('question'),
			'num_options' => count($options),
			'options' => json_encode($options),
			'is_closed' => false,
			'is_multiple' => (bool)$createRequest->input('is_multiple'),
			'is_public' => (bool)$createRequest->input('is_public'),
			'end_at' => null,
			'max_options' => $createRequest->input('maxoptions')
		];
		if($createRequest->input('timeout')) {
			$poll['end_at'] = new \DateTime('+'.$createRequest->input('timeout').' days');
		}
		$poll = $this->pollRepository->create($poll);

		if($poll)
		{
			return redirect()->route('topics.show', ['slug' => $topic->slug, 'id' => $topic->id]);
		}

		return new \Exception(trans('errors.error_creating_poll')); // TODO: Redirect back with error...
	}

	public function vote($topicSlug = null, $topicId = 0, $id = 0, Request $voteRequest) {
		$topic = $this->topicRepository->find($topicId);
		if(!$topic) {
			throw new NotFoundHttpException(trans('errors.topic_not_found'));
		}

		$poll = $this->pollRepository->find($id);
		$pollPresenter = new PollPresenter($poll);

		if(!$poll || $poll->topic_id != $topic->id) {
			throw new NotFoundHttpException(trans('errors.poll_not_found'));
		}

		if($this->guard->check()) {
			$myVote = $this->pollVoteRepository->findForUserPoll($this->guard->user(), $poll);
			if($myVote) {
				// Error
				throw new \Exception(trans('errors.you_already_vote'));
			}
		}

		$votes = $voteRequest->input('option');

		$options = $pollPresenter->options();

		if($poll->is_multiple) {
			if(!is_array($votes)) {
				$votes = [$votes];
			}
			$votes = array_unique($votes, SORT_NUMERIC);

			if($poll->max_options && count($votes) > $poll->max_options) {
				// Error
				throw new \Exception(trans('errors.poll_very_votes', ['count' => $poll->max_options]));
			}
			if(count($votes) == 0) {
				// Error
				throw new \Exception(trans('errors.poll_no_votes'));
			}
			$okVotes = [];
			foreach($votes as $vote) {
				if(is_numeric($vote) && 0 < $vote && $vote <= $pollPresenter->num_options()) {
					$options[$vote-1]->votes++;
					$okVotes[] = $vote;
				}
			}

			$votes = implode(',', $okVotes);
		}
		else
		{
			if(is_array($votes)) {
				$votes = $votes[0];
			}

			if(!is_numeric($votes) || $votes > count($options)) {
				// Error
				throw new \Exception(trans('errors.poll_invalid_vote'));
			}
			$options[$votes-1]->votes++;
		}
		$vote = $this->pollVoteRepository->create([
			'poll_id' => $poll->id,
			'vote' => $votes
		]);

		if($vote) {
			$poll->update(['options' => json_encode($options)]);
			return redirect()->route('polls.show', [$topicSlug, $topicId, $id]);
		}
	}



	public function undo($topicSlug = null, $topicId = 0, $id = 0) {
		$topic = $this->topicRepository->find($topicId);
		if(!$topic) {
			throw new NotFoundHttpException(trans('errors.topic_not_found'));
		}

		$poll = $this->pollRepository->find($id);
		$pollPresenter = new PollPresenter($poll);

		if(!$poll || $poll->topic_id != $topic->id) {
			throw new NotFoundHttpException(trans('errors.poll_not_found'));
		}

		if(!$this->guard->check()) {
			throw new \Exception(trans('errors.poll_guest_undo'));
		}

		$vote = $this->pollVoteRepository->findForUserPoll($this->guard->user(), $poll);
		if(!$vote) {
			// Error
			throw new \Exception(trans('errors.poll_nothing_to_undo'));
		}

		$votes = explode(',', $vote->vote);

		$options = $pollPresenter->options();

		foreach($votes as $option) {
			if(is_numeric($option) && 0 < $option && $option <= $pollPresenter->num_options()) {
				$options[$option-1]->votes--;
			}
		}

		$poll->update(['options' => json_encode($options)]);
		$vote->delete();

		return redirect()->route('polls.show', [$topicSlug, $topicId, $id]);
	}
}
