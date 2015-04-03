<?php

namespace MyBB\Core\Services;

use MyBB\Core\Database\Repositories\IPostRepository;
use MyBB\Core\Database\Models\Topic;

class TopicDeleter
{
	/** @var IPostRepository $postRepository */
	private $postRepository;

	/**
	 * @param IPostRepository $postRepository
	 */
	public function __construct(IPostRepository $postRepository)
	{
		$this->postRepository = $postRepository;
	}

	/**
	 * @param Topic $topic
	 *
	 * @return mixed
	 */
	public function deleteTopic(Topic $topic)
	{
		// First we need to remove old foreign keys - otherwise we can't delete posts
		$topic->update([
			'first_post_id' => null,
			'last_post_id' => null
		]);

		// Now delete the posts for this topic
		$this->postRepository->deletePostsForTopic($topic);

		// And finally delete the topic
		return $topic->forceDelete();
	}
}
