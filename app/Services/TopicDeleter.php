<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Services;

use MyBB\Core\Database\Models\Topic;
use MyBB\Core\Database\Repositories\ForumRepositoryInterface;
use MyBB\Core\Database\Repositories\PollRepositoryInterface;
use MyBB\Core\Database\Repositories\PostRepositoryInterface;

class TopicDeleter
{
    /**
     * @var PostRepositoryInterface
     */
    private $postRepository;

    /**
     * @var ForumRepositoryInterface
     */
    private $forumRepository;

    /**
     * @var PollRepositoryInterface
     */
    private $pollRepository;

    /**
     * @param PostRepositoryInterface $postRepository
     * @param ForumRepositoryInterface $forumRepository
     * @param PollRepositoryInterface $pollRepository
     */
    public function __construct(
        PostRepositoryInterface $postRepository,
        ForumRepositoryInterface $forumRepository,
        PollRepositoryInterface $pollRepository
    ) {
        $this->postRepository = $postRepository;
        $this->forumRepository = $forumRepository;
        $this->pollRepository = $pollRepository;
    }

    /**
     * @param Topic $topic
     *
     * @return bool
     */
    public function deleteTopic(Topic $topic)
    {
        if ($topic->deleted_at == null) {
            $topic->forum->decrement('num_topics');
            $topic->forum->decrement('num_posts', $topic->num_posts);

            if ($topic->user_id > 0) {
                $topic->author->decrement('num_topics');
            }

            $success = $topic->delete();

            if ($success) {
                if ($topic->last_post_id == $topic->forum->last_post_id) {
                    $this->forumRepository->updateLastPost($topic->forum);
                }
            }

            return $success;
        } else {
            // First we need to remove old foreign keys - otherwise we can't delete posts
            $topic->update([
                'first_post_id' => null,
                'last_post_id'  => null,
            ]);

            // Now delete the posts for this topic
            $this->postRepository->deletePostsForTopic($topic);

            // Don't forget the polls
            if ($topic->has_poll) {
                $this->pollRepository->remove($topic->poll);
            }

            // And finally delete the topic
            $topic->forceDelete();
        }

        return true;
    }
}
