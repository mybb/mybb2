<?php

namespace MyBB\Core\Moderation\Moderations;

use Mockery;
use MyBB\Core\Test\ClassAssertionsTrait;

class MovePostTest extends \PHPUnit_Framework_TestCase
{
	use ClassAssertionsTrait;

	public function testCanBeConstructed()
	{
		$topicRepository = Mockery::mock('MyBB\Core\Database\Repositories\TopicRepositoryInterface');
		$movePost = new MovePost($topicRepository);
		static::assertInstanceOf('MyBB\Core\Moderation\Moderations\MovePost', $movePost);
	}

	public function testHasKeyThatIsAString()
	{
		$topicRepository = Mockery::mock('MyBB\Core\Database\Repositories\TopicRepositoryInterface');
		$movePost = new MovePost($topicRepository);
		static::assertInternalType('string', $movePost->getKey());
	}

	public function testCanMovePost()
	{
		$post = Mockery::mock('MyBB\Core\Database\Models\Post');
		$topic = Mockery::mock('MyBB\Core\Database\Models\Topic');
		$topicRepository = Mockery::mock('MyBB\Core\Database\Repositories\TopicRepositoryInterface');
		$topicRepository->shouldReceive('movePostToTopic')
			->with($post, $topic)
			->once();

		$movePost = new MovePost($topicRepository);
		$movePost->move($post, $topic);
	}

	public function testIsSupportedForValidContentAndOptions()
	{
		$post = Mockery::mock('MyBB\Core\Database\Models\Post');
		$topicRepository = Mockery::mock('MyBB\Core\Database\Repositories\TopicRepositoryInterface');
		$movePost = new MovePost($topicRepository);
		static::assertTrue($movePost->supports($post, ['topic_id' => 1]));
	}

	public function testDoesNotSupportInvalidContentAndOptions()
	{
		$topicRepository = Mockery::mock('MyBB\Core\Database\Repositories\TopicRepositoryInterface');
		$movePost = new MovePost($topicRepository);

		$post = Mockery::mock('MyBB\Core\Database\Models\Post');

		static::assertFalse($movePost->supports(1));
		static::assertFalse($movePost->supports('1'));
		static::assertFalse($movePost->supports('foo'));
		static::assertFalse($movePost->supports([]));
		static::assertFalse($movePost->supports(new \stdClass()));
		static::assertFalse($movePost->supports($post, ['forum_id' => 1]));
	}

	public function testCanBeAppliedToSupportedContent()
	{
		$post = Mockery::mock('MyBB\Core\Database\Models\Post');
		$topic = Mockery::mock('MyBB\Core\Database\Models\Topic');

		$topicRepository = Mockery::mock('MyBB\Core\Database\Repositories\TopicRepositoryInterface');
		$topicRepository->shouldReceive('find')
			->with(1)
			->andReturn($topic);
		$topicRepository->shouldReceive('movePostToTopic')
			->with($post, $topic)
			->once();

		$movePost = new MovePost($topicRepository);
		static::assertNull($movePost->apply($post, ['topic_id' => 1]));
	}

	public function testIsNotAppliedToUnsupportedContent()
	{
		$topicRepository = Mockery::mock('MyBB\Core\Database\Repositories\TopicRepositoryInterface');
		$topicRepository->shouldReceive('movePostToTopic')
			->never();

		$post = Mockery::mock('MyBB\Core\Database\Models\Post');

		$movePost = new MovePost($topicRepository);

		static::assertNull($movePost->apply(1));
		static::assertNull($movePost->apply('1'));
		static::assertNull($movePost->apply('foo'));
		static::assertNull($movePost->apply([]));
		static::assertNull($movePost->apply(new \stdClass()));
		static::assertNull($movePost->apply($post, []));
	}

	public function testIsVisibleForValidContent()
	{
		$topicRepository = Mockery::mock('MyBB\Core\Database\Repositories\TopicRepositoryInterface');
		$movePost = new MovePost($topicRepository);

		static::assertTrue($movePost->visible(Mockery::mock('MyBB\Core\Database\Models\Post')));
	}

	public function testIsNotVisibleForInvalidContent()
	{
		$topicRepository = Mockery::mock('MyBB\Core\Database\Repositories\TopicRepositoryInterface');
		$movePost = new MovePost($topicRepository);

		static::assertFalse($movePost->supports(1));
		static::assertFalse($movePost->supports('1'));
		static::assertFalse($movePost->supports('foo'));
		static::assertFalse($movePost->supports(new \stdClass()));
	}

	public function testCanGetNameAsString()
	{
		$topicRepository = Mockery::mock('MyBB\Core\Database\Repositories\TopicRepositoryInterface');
		$movePost = new MovePost($topicRepository);

		static::assertInternalType('string', $movePost->getName());
	}

	public function testCanGetPresenterClassAsString()
	{
		$topicRepository = Mockery::mock('MyBB\Core\Database\Repositories\TopicRepositoryInterface');
		$movePost = new MovePost($topicRepository);

		static::assertInternalType('string', $movePost->getPresenterClass());
	}

	public function testCanGetValidPresenterClassName()
	{
		$topicRepository = Mockery::mock('MyBB\Core\Database\Repositories\TopicRepositoryInterface');
		$movePost = new MovePost($topicRepository);

		static::assertClassExtends($movePost->getPresenterClass(), 'McCool\LaravelAutoPresenter\BasePresenter');
	}

	public function testCanGetPermissionNameAsString()
	{
		$topicRepository = Mockery::mock('MyBB\Core\Database\Repositories\TopicRepositoryInterface');
		$movePost = new MovePost($topicRepository);

		static::assertInternalType('string', $movePost->getPermissionName());
	}
}
