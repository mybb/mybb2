<?php

namespace MyBB\Core\Moderation\Moderations;

use MyBB\Core\Database\Models\Post;
use MyBB\Core\Database\Repositories\PostRepositoryInterface;
use MyBB\Core\Moderation\ArrayModerationInterface;

class MergePosts implements ArrayModerationInterface
{
	/**
	 * @var PostRepositoryInterface
	 */
	protected $postRepository;

	/**
	 * @param PostRepositoryInterface $postRepository
	 */
	public function __construct(PostRepositoryInterface $postRepository)
	{
		$this->postRepository = $postRepository;
	}

	/**
	 * @return string
	 */
	public function getKey()
	{
		return 'merge_posts';
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return 'Merge';
	}

	/**
	 * @return string
	 */
	public function getIcon()
	{
		return 'fa-code-fork';
	}

	/**
	 * @param array $posts
	 */
	public function merge(array $posts)
	{
		$this->postRepository->mergePosts($posts);
	}

	/**
	 * @param mixed $content
	 * @param array $options
	 *
	 * @return mixed
	 */
	public function apply($content, array $options = [])
	{
		$this->merge($content);
	}

	/**
	 * @param mixed $content
	 * @param array $options
	 *
	 * @return bool
	 */
	public function supports($content, array $options = [])
	{
		return is_array_of($content, 'MyBB\Core\Database\Models\Post');
	}

	/**
	 * @param mixed $content
	 *
	 * @return bool
	 */
	public function visible($content)
	{
		return $content instanceof Post;
	}
}
