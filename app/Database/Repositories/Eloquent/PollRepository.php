<?php
/**
 * Poll repository implementation, using Eloquent ORM.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Database\Repositories\Eloquent;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\DatabaseManager;
use MyBB\Core\Database\Models\Poll;
use MyBB\Core\Database\Models\Topic;
use MyBB\Core\Database\Repositories\PollRepositoryInterface;
use MyBB\Core\Database\Repositories\PollVoteRepositoryInterface;

class PollRepository implements PollRepositoryInterface
{
    /**
     * @var Poll $pollModel
     * @access protected
     */
    protected $pollModel;

    /**
     * @var PollVoteRepositoryInterface $pollVoteRepository
     * @access protected
     */
    protected $pollVoteRepository; // TODO: https://github.com/mybb/mybb2/pull/32#discussion_r28090844

    /**
     * @var Guard $guard ;
     * @access protected
     */
    protected $guard;

    /**
     * @var DatabaseManager $dbManager
     * @access private
     */
    private $dbManager;

    /**
     * @param Poll $pollModel The model to use for polls.
     * @param Guard $guard Laravel guard instance, used to get user ID.
     * @param DatabaseManager $dbManager Database manager, needed to do transactions.
     * @param PollVoteRepositoryInterface $pollVoteRepository The poll vote repository for poll votes
     */
    public function __construct(
        Poll $pollModel,
        Guard $guard,
        DatabaseManager $dbManager,
        PollVoteRepositoryInterface $pollVoteRepository
    ) {
        $this->pollModel = $pollModel;
        $this->guard = $guard;
        $this->dbManager = $dbManager;
        $this->pollVoteRepository = $pollVoteRepository;
    }

    /**
     * Find a single poll by ID.
     *
     * @param string $id The ID of the poll to find.
     *
     * @return Poll
     */
    public function find($id)
    {
        return $this->pollModel->with(['author', 'topic'])->find($id);
    }

    /**
     * Create a new poll
     *
     * @param array $details Details about the poll.
     *
     * @return Poll
     */
    public function create(array $details = [])
    {
        $details = array_merge([
            'user_id' => $this->guard->user()->getAuthIdentifier(),
        ], $details);

        if ($details['user_id'] <= 0) {
            $details['user_id'] = null;
        }

        $poll = $this->pollModel->create($details);

        return $poll;
    }

    /**
     * Find poll of a topic
     *
     * @param Topic $topic
     *
     * @return Poll
     */
    public function getForTopic(Topic $topic)
    {
        return $this->pollModel->with(['author'])->where('topic_id', $topic->id)->first();
    }

    /**
     * Remove the poll
     * @param Poll $poll
     *
     * @return bool
     */
    public function remove(Poll $poll)
    {
        $this->dbManager->transaction(function () use (&$poll) {
            $this->pollVoteRepository->removeAllByPoll($poll);
            $poll->delete();
        });
        if ($poll) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Edit a poll
     *
     * @param Poll $poll The poll to edit
     * @param array $pollDetails The details of the poll.
     *
     * @return Poll
     */
    public function editPoll(Poll $poll, array $pollDetails)
    {
        $poll->update($pollDetails);

        return $poll;
    }
}
