<?php
/**
 * Poll presenter class.
 *
 * @version 2.0.0
 * @author  MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Presenters;

use McCool\LaravelAutoPresenter\BasePresenter;
use Illuminate\Auth\Guard;
use MyBB\Core\Database\Models\Poll as PollModel;
use MyBB\Core\Database\Repositories\PollVoteRepositoryInterface;

class Poll extends BasePresenter
{
    /** @var PollModel $wrappedObject */
    protected $wrappedObject;

    /** @var  PollVoteRepositoryInterface $pollVoteRepository */
    protected $pollVoteRepository;

    /** @var Guard $guard */
    private $guard;

    /** @var array $cache */
    protected $cache = [];

    /**
     * @param PollModel $resource
     * @param PollVoteRepositoryInterface $pollVoteRepository
     * @param Guard $guard
     */
    public function __construct(
        PollModel $resource,
        PollVoteRepositoryInterface $pollVoteRepository,
        Guard $guard
    ) {
        $this->wrappedObject = $resource;
        $this->pollVoteRepository = $pollVoteRepository;
        $this->guard = $guard;
    }

    /**
     * get the options of the poll
     *
     * @return array
     */
    public function options()
    {
        if (!isset($this->cache['options'])) {
            $this->cache['options'] = $this->wrappedObject->options;
            for ($i = 0; $i < count($this->cache['options']); $i++) {
                $this->cache['options'][$i]['voted'] = false;
            }
            if ($this->myVote()) {
                $votes = explode(',', $this->myVote->vote);
                foreach ($votes as $vote) {
                    $this->cache['options'][$vote - 1]['voted'] = true;
                }
            }
        }

        return $this->cache['options'];
    }

    /**
     * @return integer
     */
    public function num_votes()
    {
        if (!isset($this->cache['num_votes'])) {
            $options = $this->options();
            $votes = 0;
            foreach ($options as $option) {
                $votes += $option['votes'];
            }
            $this->cache['num_votes'] = $votes;
        }

        return $this->cache['num_votes'];
    }

    /**
     * @return integer
     */
    public function num_options()
    {
        if (!isset($this->cache['num_options'])) {
            $this->cache['num_options'] = count($this->options());
        }

        return $this->cache['num_options'];
    }

    /**
     * @return bool
     */
    public function is_closed()
    {
        return ($this->wrappedObject->is_closed ||
            ($this->wrappedObject->end_at && new \DateTime($this->wrappedObject->end_at) < new \DateTime)
        );
    }

    /**
     * @return \DateTime|null
     */
    public function end_at()
    {
        if ($this->wrappedObject->end_at && new \DateTime($this->wrappedObject->end_at) >= new \DateTime) {
            return $this->wrappedObject->end_at;
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function myVote()
    {
        if (!isset($this->cache['myVote'])) {
            if ($this->guard->check()) {
                $this->cache['myVote'] = $this->pollVoteRepository->findForUserPoll($this->guard->user(),
                    $this->wrappedObject);
            } else {
                $this->cache['myVote'] = null;
            }
        }

        return $this->cache['myVote'];
    }
}
