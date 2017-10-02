<?php
/**
 * PollVote repository implementation, using Eloquent ORM.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories\Eloquent;

use Illuminate\Contracts\Auth\Guard;
use MyBB\Core\Database\Models\{
    Poll, PollVote, User
};
use MyBB\Core\Database\Repositories\PollVoteRepositoryInterface;

class PollVoteRepository implements PollVoteRepositoryInterface
{
    /**
     * @var PollVote $voteModel
     */
    protected $voteModel;
    /**
     * @var Guard $guard ;
     */
    protected $guard;

    /**
     * @param PollVote $voteModel The model to use for poll votes.
     * @param Guard $guard Laravel guard instance, used to get user ID.
     */
    public function __construct(
        PollVote $voteModel,
        Guard $guard
    ) {
        $this->voteModel = $voteModel;
        $this->guard = $guard;
    }

    /**
     * Find a single poll vote by ID.
     *
     * @param int $id The ID of the vote to find.
     *
     * @return PollVote
     */
    public function find(int $id) : PollVote
    {
        return $this->voteModel->with(['author', 'poll'])->find($id);
    }

    /**
     * Create a new poll vote
     *
     * @param array $details Details about the poll.
     *
     * @return PollVote
     */
    public function create(array $details = []) : PollVote
    {
        $details = array_merge([
            'user_id' => $this->guard->user()->getAuthIdentifier(),
        ], $details);

        if ($details['user_id'] < 0) {
            $details['user_id'] = null;
        }

        $vote = $this->voteModel->create($details);

        return $vote;
    }

    /**
     * @param User $user
     * @param Poll $poll
     *
     * @return PollVote
     */
    public function findForUserPoll(User $user, Poll $poll) : PollVote
    {
        return $this->voteModel->where('user_id', $user->id)->where('poll_id', $poll->id)->first();
    }

    /**
     * @param Poll $poll
     *
     * @return \Illuminate\Support\Collection
     */
    public function allForPoll(Poll $poll)
    {
        return $this->voteModel->where('poll_id', $poll->id)->get();
    }

    /**
     * @param Poll $poll
     *
     * @return \Illuminate\Support\Collection
     */
    public function removeAllByPoll(Poll $poll)
    {
        return $this->voteModel->where('poll_id', $poll->id)->delete();
    }
}
