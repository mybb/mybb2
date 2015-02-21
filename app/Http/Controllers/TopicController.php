<?php
/**
 * Topic Controller.
 *
 * Used to view, create, delete and update topics.
 *
 * @version 2.0.0
 * @author MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Http\Controllers;

use Illuminate\Html\FormBuilder;
use MyBB\Core\Database\Models\Topic;
use MyBB\Core\Database\Repositories\IForumRepository;
use MyBB\Core\Database\Repositories\IPostRepository;
use MyBB\Core\Database\Repositories\ITopicRepository;
use MyBB\Core\Http\Requests\Topic\CreateRequest;
use MyBB\Core\Http\Requests\Topic\ReplyRequest;
use PhpSpec\Exception\Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TopicController extends Controller
{
    /** @var ITopicRepository $topicRepository */
    private $topicRepository;
    /** @var IPostRepository $postRepository */
    private $postRepository;
    /** @var IForumRepository $forumRepository */
    private $forumRepository;

    /**
     * @param ITopicRepository $topicRepository Topic repository instance, used to fetch topic details.
     * @param IPostRepository  $postRepository  Post repository instance, used to fetch post details.
     * @param IForumRepository $forumRepository Forum repository interface, used to fetch forum details.
     */
    public function __construct(ITopicRepository $topicRepository, IPostRepository $postRepository, IForumRepository $forumRepository)
    {
        $this->topicRepository = $topicRepository;
        $this->postRepository = $postRepository;
        $this->forumRepository = $forumRepository;
    }

    public function show($slug = '')
    {
        $topic = $this->topicRepository->findBySlug($slug);

        if (!$topic) {
            throw new NotFoundHttpException('Topic not found');
        }

        $this->topicRepository->incrementViewCount($topic);

        $posts = $this->postRepository->allForTopic($topic);

        return view('topic.show', compact('topic', 'posts'));
    }

    public function reply($slug = '')
    {
        $topic = $this->topicRepository->findBySlug($slug);

        if (!$topic) {
            throw new NotFoundHttpException('Topic not found');
        }

        return view('topic.reply', compact('topic'));
    }

    public function postReply($slug = '', ReplyRequest $replyRequest)
    {
        /** @var Topic $topic */
        $topic = $this->topicRepository->findBySlug($slug);

        if (!$topic) {
            throw new NotFoundHttpException('Topic not found');
        }

        $post = $this->postRepository->addPostToTopic($topic, [
            'content' => $replyRequest->input('content'),
        ]);

        if ($post) {
            return redirect()->route('topics.show', ['slug' => $topic->slug]);
        }

        return new \Exception('Error creating post'); // TODO: Redirect back with error...
    }

    public function create($forumId)
    {
        $forum = $this->forumRepository->find($forumId);

        if (!$forum) {
            throw new NotFoundHttpException('Forum not found');
        }

        return view('topic.create', compact('forum'));
    }

    public function postCreate($forumId = 0, CreateRequest $createRequest)
    {
        $topic =  $this->topicRepository->create([
                                                     'title' => $createRequest->input('title'),
                                                     'forum_id' => $createRequest->input('forum_id'),
                                                     'first_post_id' => 0,
                                                     'last_post_id' => 0,
                                                     'views' => 0,
                                                     'num_posts' => 0,
                                                     'content' => $createRequest->input('content'),
                                                 ]);

        if ($topic) {
            return redirect()->route('topics.show', ['slug' => $topic->slug]);
        }

        return new \Exception('Error creating topic'); // TODO: Redirect back with error...
    }
}
