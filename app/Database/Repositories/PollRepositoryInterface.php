<?php
/**
 * Poll repository contract.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories;

use MyBB\Core\Database\Models\{
    Poll, Topic
};

interface PollRepositoryInterface
{
    /**
     * Find a single poll by ID.
     *
     * @param int $id The ID of the poll to find.
     *
     * @return \MyBB\Core\Database\Models\Poll
     */
    public function find(int $id) : Poll;

    /**
     * Create a new poll
     *
     * @param array $details Details about the poll.
     *
     * @return \MyBB\Core\Database\Models\Poll
     */
    public function create(array $details = []) : Poll;

    /**
     * Find poll of a topic
     *
     * @param Topic $topic
     *
     * @return \MyBB\Core\Database\Models\Poll
     */
    public function getForTopic(Topic $topic) : Poll;

    /**
     * Remove the poll
     *
     * @param Poll $poll
     *
     * @return bool
     */
    public function remove(Poll $poll) : bool;

    /**
     * Edit a poll
     *
     * @param Poll $poll The poll to edit
     * @param array $pollDetails The details of the poll.
     *
     * @return Poll
     */
    public function editPoll(Poll $poll, array $pollDetails) : Poll;
}
