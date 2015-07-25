<?php

namespace MyBB\Core\Moderation\Moderations;

use Mockery;
use MyBB\Core\Test\ClassAssertionsTrait;

class DeleteTopicTest extends \PHPUnit_Framework_TestCase
{
	use ClassAssertionsTrait;

	public function testCanBeConstructed()
	{
		$topicDeleter = Mockery::mock('MyBB\Core\Services\TopicDeleter');
		$deleteTopic = new DeleteTopic($topicDeleter);
		static::assertInstanceOf('MyBB\Core\Moderation\Moderations\DeleteTopic', $deleteTopic);
	}

	public function testHasKeyThatIsAString()
	{
		$topicDeleter = Mockery::mock('MyBB\Core\Services\TopicDeleter');
		$deleteTopic = new DeleteTopic($topicDeleter);
		static::assertInternalType('string', $deleteTopic->getKey());
	}

	public function testCanDeleteTopic()
	{
		$topic = Mockery::mock('MyBB\Core\Database\Models\Topic');

		$topicDeleter = Mockery::mock('MyBB\Core\Services\TopicDeleter');
		$topicDeleter->shouldReceive('deleteTopic')
			->with($topic)
			->andReturn(true);

		$deleteTopic = new DeleteTopic($topicDeleter);
		$deleteTopic->deleteTopic($topic);
	}

	public function testIsSupportedForValidContent()
	{
		$topic = Mockery::mock('MyBB\Core\Database\Models\Topic');
		$topicDeleter = Mockery::mock('MyBB\Core\Services\TopicDeleter');
		$deleteTopic = new DeleteTopic($topicDeleter);
		static::assertTrue($deleteTopic->supports($topic));
	}

	public function testDoesNotSupportInvalidContent()
	{
		$topicDeleter = Mockery::mock('MyBB\Core\Services\TopicDeleter');
		$deleteTopic = new DeleteTopic($topicDeleter);

		static::assertFalse($deleteTopic->supports(1));
		static::assertFalse($deleteTopic->supports('1'));
		static::assertFalse($deleteTopic->supports('foo'));
		static::assertFalse($deleteTopic->supports([]));
		static::assertFalse($deleteTopic->supports(new \stdClass()));
	}

	public function testCanBeAppliedToSupportedContent()
	{
		$topic = Mockery::mock('MyBB\Core\Database\Models\Topic');
		$topicDeleter = Mockery::mock('MyBB\Core\Services\TopicDeleter');
		$topicDeleter->shouldReceive('deleteTopic')
			->with($topic)
			->andReturn(true);

		$deleteTopic = new DeleteTopic($topicDeleter);
		static::assertNull($deleteTopic->apply($topic));
	}

	public function testIsNotAppliedToUnsupportedContent()
	{
		$topicDeleter = Mockery::mock('MyBB\Core\Services\TopicDeleter');
		$deleteTopic = new DeleteTopic($topicDeleter);

		static::assertNull($deleteTopic->apply(1));
		static::assertNull($deleteTopic->apply('1'));
		static::assertNull($deleteTopic->apply('foo'));
		static::assertNull($deleteTopic->apply([]));
		static::assertNull($deleteTopic->apply(new \stdClass()));
	}

	public function testIsVisibleForValidContent()
	{
		$topicDeleter = Mockery::mock('MyBB\Core\Services\TopicDeleter');
		$deleteTopic = new DeleteTopic($topicDeleter);

		static::assertTrue($deleteTopic->visible(Mockery::mock('MyBB\Core\Database\Models\Topic')));
	}

	public function testIsNotVisibleForInvalidContent()
	{
		$topicDeleter = Mockery::mock('MyBB\Core\Services\TopicDeleter');
		$deleteTopic = new DeleteTopic($topicDeleter);

		static::assertFalse($deleteTopic->supports(1));
		static::assertFalse($deleteTopic->supports('1'));
		static::assertFalse($deleteTopic->supports('foo'));
		static::assertFalse($deleteTopic->supports(new \stdClass()));
	}

	public function testCanGetNameAsString()
	{
		$topicDeleter = Mockery::mock('MyBB\Core\Services\TopicDeleter');
		$deleteTopic = new DeleteTopic($topicDeleter);

		static::assertInternalType('string', $deleteTopic->getName());
	}

	public function testCanGetPresenterClassAsString()
	{
		$topicDeleter = Mockery::mock('MyBB\Core\Services\TopicDeleter');
		$deleteTopic = new DeleteTopic($topicDeleter);

		static::assertInternalType('string', $deleteTopic->getPresenterClass());
	}

	public function testCanGetValidPresenterClassName()
	{
		$topicDeleter = Mockery::mock('MyBB\Core\Services\TopicDeleter');
		$deleteTopic = new DeleteTopic($topicDeleter);

		static::assertClassExtends($deleteTopic->getPresenterClass(), 'McCool\LaravelAutoPresenter\BasePresenter');
	}
}
