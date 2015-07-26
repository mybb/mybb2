<?php

namespace MyBB\Core\Moderation\Moderations;

use Mockery;
use MyBB\Core\Test\ClassAssertionsTrait;

class MoveTopicTest extends \PHPUnit_Framework_TestCase
{
	use ClassAssertionsTrait;

	public function testCanBeConstructed()
	{
		$forumRepository = Mockery::mock('MyBB\Core\Database\Repositories\ForumRepositoryInterface');
		$moveTopic = new MoveTopic($forumRepository);
		static::assertInstanceOf('MyBB\Core\Moderation\Moderations\MoveTopic', $moveTopic);
	}

	public function testHasKeyThatIsAString()
	{
		$forumRepository = Mockery::mock('MyBB\Core\Database\Repositories\ForumRepositoryInterface');
		$moveTopic = new MoveTopic($forumRepository);
		static::assertInternalType('string', $moveTopic->getKey());
	}

	public function testCanMoveTopic()
	{
		$topic = Mockery::mock('MyBB\Core\Database\Models\Topic');
		$forum = Mockery::mock('MyBB\Core\Database\Models\Forum');

		$forumRepository = Mockery::mock('MyBB\Core\Database\Repositories\ForumRepositoryInterface');
		$forumRepository->shouldReceive('moveTopicToForum')
			->with($topic, $forum)
			->once();

		$moveTopic = new MoveTopic($forumRepository);
		$moveTopic->moveTopic($topic, $forum);
	}

	public function testIsSupportedForValidContentAndOptions()
	{
		$topic = Mockery::mock('MyBB\Core\Database\Models\Topic');
		$forumRepository = Mockery::mock('MyBB\Core\Database\Repositories\ForumRepositoryInterface');
		$moveTopic = new MoveTopic($forumRepository);
		static::assertTrue($moveTopic->supports($topic, ['forum_id' => 1]));
	}

	public function testDoesNotSupportInvalidContentAndOptions()
	{
		$forumRepository = Mockery::mock('MyBB\Core\Database\Repositories\ForumRepositoryInterface');
		$moveTopic = new MoveTopic($forumRepository);

		$topic = Mockery::mock('MyBB\Core\Database\Models\Topic');

		static::assertFalse($moveTopic->supports(1));
		static::assertFalse($moveTopic->supports('1'));
		static::assertFalse($moveTopic->supports('foo'));
		static::assertFalse($moveTopic->supports([]));
		static::assertFalse($moveTopic->supports(new \stdClass()));
		static::assertFalse($moveTopic->supports($topic, ['post_id' => 1]));
	}

	public function testCanBeAppliedToSupportedContent()
	{
		$topic = Mockery::mock('MyBB\Core\Database\Models\Topic');
		$forum = Mockery::mock('MyBB\Core\Database\Models\Forum');

		$forumRepository = Mockery::mock('MyBB\Core\Database\Repositories\ForumRepositoryInterface');
		$forumRepository->shouldReceive('find')
			->with(1)
			->andReturn($forum);
		$forumRepository->shouldReceive('moveTopicToForum')
			->with($topic, $forum)
			->once();

		$moveTopic = new MoveTopic($forumRepository);
		static::assertNull($moveTopic->apply($topic, ['forum_id' => 1]));
	}

	public function testIsNotAppliedToUnsupportedContent()
	{
		$forumRepository = Mockery::mock('MyBB\Core\Database\Repositories\ForumRepositoryInterface');
		$forumRepository->shouldReceive('moveTopicToForum')
			->never();

		$topic = Mockery::mock('MyBB\Core\Database\Models\Topic');

		$moveTopic = new MoveTopic($forumRepository);

		static::assertNull($moveTopic->apply(1));
		static::assertNull($moveTopic->apply('1'));
		static::assertNull($moveTopic->apply('foo'));
		static::assertNull($moveTopic->apply([]));
		static::assertNull($moveTopic->apply(new \stdClass()));
		static::assertNull($moveTopic->apply($topic, []));
	}

	public function testIsVisibleForValidContent()
	{
		$forumRepository = Mockery::mock('MyBB\Core\Database\Repositories\ForumRepositoryInterface');
		$moveTopic = new MoveTopic($forumRepository);

		static::assertTrue($moveTopic->visible(Mockery::mock('MyBB\Core\Database\Models\Topic')));
	}

	public function testIsNotVisibleForInvalidContent()
	{
		$forumRepository = Mockery::mock('MyBB\Core\Database\Repositories\ForumRepositoryInterface');
		$moveTopic = new MoveTopic($forumRepository);

		static::assertFalse($moveTopic->supports(1));
		static::assertFalse($moveTopic->supports('1'));
		static::assertFalse($moveTopic->supports('foo'));
		static::assertFalse($moveTopic->supports(new \stdClass()));
	}

	public function testCanGetNameAsString()
	{
		$forumRepository = Mockery::mock('MyBB\Core\Database\Repositories\ForumRepositoryInterface');
		$moveTopic = new MoveTopic($forumRepository);

		static::assertInternalType('string', $moveTopic->getName());
	}

	public function testCanGetPresenterClassAsString()
	{
		$forumRepository = Mockery::mock('MyBB\Core\Database\Repositories\ForumRepositoryInterface');
		$moveTopic = new MoveTopic($forumRepository);

		static::assertInternalType('string', $moveTopic->getPresenterClass());
	}

	public function testCanGetValidPresenterClassName()
	{
		$forumRepository = Mockery::mock('MyBB\Core\Database\Repositories\ForumRepositoryInterface');
		$moveTopic = new MoveTopic($forumRepository);

		static::assertClassExtends($moveTopic->getPresenterClass(), 'McCool\LaravelAutoPresenter\BasePresenter');
	}

	public function testCanGetPermissionNameAsString()
	{
		$forumRepository = Mockery::mock('MyBB\Core\Database\Repositories\ForumRepositoryInterface');
		$moveTopic = new MoveTopic($forumRepository);

		static::assertInternalType('string', $moveTopic->getPermissionName());
	}
}
