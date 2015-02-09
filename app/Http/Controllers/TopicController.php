<?php
/**
 * Topic Controller.
 *
 * Used to view, create, delete and update topics.
 *
 * @version 1.0.0
 * @author MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Http\Controllers;

use MyBB\Core\Database\Repositories\IPostRepository;
use MyBB\Core\Database\Repositories\ITopicRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TopicController extends Controller
{
    /** @var ITopicRepository $topicRepository */
    private $topicRepository;
    /** @var IPostRepository $postRepository */
    private $postRepository;

    /**
     * @param ITopicRepository $topicRepository Topic repository instance, used to fetch topic details.
     * @param IPostRepository  $postRepository Post repository instance, used to fetch post details.
     */
    public function __construct(ITopicRepository $topicRepository, IPostRepository $postRepository)
    {
        $this->topicRepository = $topicRepository;
        $this->postRepository = $postRepository;
    }

    public function show($slug = '')
    {
        $topic = $this->topicRepository->findBySlug($slug);

        if (!$topic) {
            throw new NotFoundHttpException('Topic not found');
        }

        $posts = $this->postRepository->allForTopic($topic);

        return view('topic.show', compact('topic', 'posts'));
    }
}
