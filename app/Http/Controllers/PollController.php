<?php
/**
 * Poll Controller.
 *
 * Used to view, create, delete and update polls.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Controllers;

use DaveJamesMiller\Breadcrumbs\Manager as Breadcrumbs;
use Illuminate\Contracts\Auth\Guard;
use MyBB\Core\Database\Repositories\ForumRepositoryInterface;
use MyBB\Core\Database\Repositories\PollRepositoryInterface;
use MyBB\Core\Database\Repositories\PollVoteRepositoryInterface;
use MyBB\Core\Database\Repositories\TopicRepositoryInterface;
use MyBB\Core\Exceptions\PollAlreadyVotedException;
use MyBB\Core\Exceptions\PollClosedException;
use MyBB\Core\Exceptions\PollNoGuestUndoException;
use MyBB\Core\Exceptions\PollNotFoundException;
use MyBB\Core\Exceptions\PollNoUndoException;
use MyBB\Core\Exceptions\TopicNotFoundException;
use MyBB\Core\Http\Requests\Poll\CreateRequest;

class PollController extends AbstractController
{
    /**
     * @var TopicRepositoryInterface
     */
    private $topicRepository;

    /**
     * @var PollRepositoryInterface
     */
    private $pollRepository;

    /**
     * @var PollVoteRepositoryInterface
     */
    private $pollVoteRepository;

    /**
     * @var ForumRepositoryInterface
     */
    private $forumRepository;

    /**
     * @var Guard
     */
    private $guard;

    /**
     * @var Breadcrumbs
     */
    private $breadcrumbs;

    /**
     * @param TopicRepositoryInterface $topicRepository Topic repository instance, used to fetch topic details.
     * @param PollRepositoryInterface $pollRepository Poll repository instance, used to fetch poll details.
     * @param PollVoteRepositoryInterface $pollVoteRepository PollVote repository instance, used to fetch poll vote
     *                                                        details.
     * @param ForumRepositoryInterface $forumRepository Forum repository interface, used to fetch forum details.
     * @param Guard $guard Guard implementation
     * @param Breadcrumbs $breadcrumbs
     */
    public function __construct(
        TopicRepositoryInterface $topicRepository,
        PollRepositoryInterface $pollRepository,
        PollVoteRepositoryInterface $pollVoteRepository,
        ForumRepositoryInterface $forumRepository,
        Guard $guard,
        Breadcrumbs $breadcrumbs
    ) {
        $this->topicRepository = $topicRepository;
        $this->pollRepository = $pollRepository;
        $this->pollVoteRepository = $pollVoteRepository;
        $this->forumRepository = $forumRepository;
        $this->guard = $guard;
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * @param  int $topicId
     * @param  string $topicSlug
     *
     * @return \Illuminate\View\View
     */
    public function show(int $topicId, string $topicSlug)
    {
        $topic = $this->topicRepository->find($topicId);
        if (!$topic) {
            throw new TopicNotFoundException;
        }

        if (!$topic->has_poll) {
            throw new PollNotFoundException;
        }

        $poll = $topic->poll;
        $pollPresenter = app()->make('MyBB\Core\Presenters\PollPresenter', [$poll]);

        $this->breadcrumbs->setCurrentRoute('polls.show', $topic);

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

        return view('polls.show', compact('topic', 'options', 'poll'));
    }

    /**
     * @param int $id
     * @param string $slug
     *
     * @return \Illuminate\View\View
     */
    public function create(int $id, string $slug)
    {
        $topic = $this->topicRepository->find($id);

        if (!$topic) {
            throw new TopicNotFoundException;
        }

        $this->breadcrumbs->setCurrentRoute('polls.create', $topic);

        return view('polls.create', compact('topic'));
    }

    /**
     * @param int $id
     * @param string $slug
     * @param CreateRequest $createRequest
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postCreate(int $id, string $slug, CreateRequest $createRequest)
    {
        $topic = $this->topicRepository->find($id);

        if (!$topic) {
            throw new TopicNotFoundException;
        }

        $this->breadcrumbs->setCurrentRoute('polls.create', $topic);

        $poll = [
            'topic_id'    => $id,
            'question'    => $createRequest->input('question'),
            'num_options' => count($createRequest->options()),
            'options'     => $createRequest->options(),
            'is_closed'   => false,
            'is_multiple' => (bool)$createRequest->input('is_multiple'),
            'is_public'   => (bool)$createRequest->input('is_public'),
            'end_at'      => null,
            'max_options' => (int)$createRequest->input('maxoptions'),
        ];
        if ($createRequest->input('endAt')) {
            $poll['end_at'] = new \DateTime($createRequest->input('endAt'));
        }
        $poll = $this->pollRepository->create($poll);

        if ($poll) {
            $this->topicRepository->setHasPoll($topic, true);

            return redirect()->route('topics.show', ['slug' => $topic->slug, 'id' => $topic->id]);
        }

        return redirect()->route('polls.create')->withInput()->withErrors([
            'error' => trans('error.error_creating_poll'),
        ]);
    }

    /**
     * @param int $topicId
     * @param string $topicSlug
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Exception
     */
    public function vote(int $topicId, string $topicSlug)
    {
        $topic = $this->topicRepository->find($topicId);
        if (!$topic) {
            throw new TopicNotFoundException;
        }

        if (!$topic->has_poll) {
            throw new PollNotFoundException;
        }

        $poll = $topic->poll;
        $pollPresenter = app()->make('MyBB\Core\Presenters\PollPresenter', [$poll]);

        $voteRequest = app()->make('MyBB\Core\Http\Requests\Poll\VoteRequest', [$pollPresenter]);

        if ($pollPresenter->is_closed) {
            throw new PollClosedException;
        }

        // Has the user already voted?
        if ($this->guard->check()) {
            $myVote = $this->pollVoteRepository->findForUserPoll($this->guard->user(), $poll);
            if ($myVote) {
                // Error
                throw new PollAlreadyVotedException;
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
            'vote'    => $votes,
        ]);

        if ($vote) {
            $this->pollRepository->editPoll($poll, ['options' => $options]);
        }

        return redirect()->route('polls.show', ['topicSlug' => $topicSlug, 'topicId' => $topicId]);
    }


    /**
     * @param int $topicId
     * @param string $topicSlug
     *
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Exception
     */
    public function undo(int $topicId, string $topicSlug)
    {
        $topic = $this->topicRepository->find($topicId);
        if (!$topic) {
            throw new TopicNotFoundException;
        }

        if (!$topic->has_poll) {
            throw new PollNotFoundException;
        }

        $poll = $topic->poll;
        $pollPresenter = app()->make('MyBB\Core\Presenters\PollPresenter', [$poll]);

        if (!$this->guard->check()) {
            throw new PollNoGuestUndoException;
        }

        if ($pollPresenter->is_closed) {
            throw new PollClosedException;
        }

        $vote = $this->pollVoteRepository->findForUserPoll($this->guard->user(), $poll);
        if (!$vote) {
            // Error
            throw new PollNoUndoException;
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

        return redirect()->route('polls.show', ['topicSlug' => $topicSlug, 'topicId' => $topicId]);
    }

    /**
     * @param int $topicId
     * @param string $topicSlug
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(int $topicId, string $topicSlug)
    {
        $topic = $this->topicRepository->find($topicId);
        if (!$topic) {
            throw new TopicNotFoundException;
        }

        if (!$topic->has_poll) {
            throw new PollNotFoundException;
        }

        $poll = $topic->poll;

        $this->pollRepository->remove($poll);

        $topic->has_poll = false;
        $topic->save();

        return redirect()->route('topics.show', ['slug' => $topicSlug, 'id' => $topicId]);
    }

    /**
     * @param int $topicId
     * @param string $topicSlug
     *
     * @return \Illuminate\View\View
     */
    public function edit(int $topicId, string $topicSlug)
    {
        $topic = $this->topicRepository->find($topicId);
        if (!$topic) {
            throw new TopicNotFoundException;
        }

        if (!$topic->has_poll) {
            throw new PollNotFoundException;
        }

        $poll = $topic->poll;

        $this->breadcrumbs->setCurrentRoute('polls.edit', $topic);

        return view('polls.edit', compact('topic', 'poll'));
    }

    /**
     * @param int $topicId
     * @param string $topicSlug
     * @param CreateRequest $createRequest
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postEdit(int $topicId, string $topicSlug, CreateRequest $createRequest)
    {
        $topic = $this->topicRepository->find($topicId);
        if (!$topic) {
            throw new TopicNotFoundException;
        }

        if (!$topic->has_poll) {
            throw new PollNotFoundException;
        }

        $poll = $topic->poll;
        $pollPresenter = app()->make('MyBB\Core\Presenters\PollPresenter', [$poll]);


        $options = [];
        $i = 0;
        foreach ($createRequest->input('option') as $option) {
            if ($option && is_scalar($option)) {
                $options[] = [
                    'option' => $option,
                    'votes'  => 0,
                ];
                if (isset($pollPresenter->options[$i]['votes'])) {
                    $options[$i]['votes'] = $pollPresenter->options[$i]['votes'];
                }
                ++$i;
            }
        }

        $pollDetails = [
            'question'    => $createRequest->input('question'),
            'num_options' => count($options),
            'options'     => $options,
            'is_closed'   => (bool)$createRequest->input('is_closed'),
            'is_multiple' => (bool)$createRequest->input('is_multiple'),
            'is_public'   => (bool)$createRequest->input('is_public'),
            'max_options' => (int)$createRequest->input('maxoptions'),
        ];
        if ($createRequest->input('endAt')) {
            $poll['end_at'] = new \DateTime($createRequest->input('endAt'));
        }

        $poll->update($pollDetails);

        if ($poll) {
            return redirect()->route('topics.show', ['slug' => $topic->slug, 'id' => $topic->id]);
        }

        return redirect()->route('polls.edit')->withInput()->withErrors(['error' => trans('error.error_editing_poll')]);
    }
}
