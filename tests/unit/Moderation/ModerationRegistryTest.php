<?php

namespace MyBB\Core\Moderation;

use Mockery;
use PHPUnit_Framework_Error;

class ModerationRegistryTest extends \PHPUnit_Framework_TestCase
{
	public function testCanBeConstructedEmpty()
	{
		$registry = new ModerationRegistry();
		static::assertInstanceOf('MyBB\Core\Moderation\ModerationRegistry', $registry);
	}

	public function testCanBeConstructedWithModerations()
	{
		$moderation = Mockery::mock('MyBB\Core\Moderation\ModerationInterface');
		$moderation->shouldReceive('getKey')
			->withNoArgs()
			->andReturn('key');

		$moderations = [$moderation];
		$registry = new ModerationRegistry($moderations);
		static::assertInstanceOf('MyBB\Core\Moderation\ModerationRegistry', $registry);
	}

	/**
	 * @expectedException PHPUnit_Framework_Error
	 */
	public function testExceptionWhenConstructingWithInvalidObjects()
	{
		$moderations = [new \stdClass()];
		new ModerationRegistry($moderations);
	}

	public function testCanAddModeration()
	{
		$moderation = Mockery::mock('MyBB\Core\Moderation\ModerationInterface');
		$moderation->shouldReceive('getKey')
			->withNoArgs()
			->andReturn('key');

		$registry = new ModerationRegistry();
		$registry->addModeration($moderation);

		static::assertCount(1, $registry->getAll());
	}

	public function testAddedModerationIsRetrievableByKey()
	{
		$moderation = Mockery::mock('MyBB\Core\Moderation\ModerationInterface');
		$moderation->shouldReceive('getKey')
			->withNoArgs()
			->andReturn('key');

		$registry = new ModerationRegistry();
		$registry->addModeration($moderation);

		static::assertEquals($moderation, $registry->get('key'));
	}

	public function testGetAllReturnsAllModerations()
	{
		$moderation1 = Mockery::mock('MyBB\Core\Moderation\ModerationInterface');
		$moderation1->shouldReceive('getKey')
			->withNoArgs()
			->andReturn('key1');

		$moderation2 = Mockery::mock('MyBB\Core\Moderation\ModerationInterface');
		$moderation2->shouldReceive('getKey')
			->withNoArgs()
			->andReturn('key2');

		$registry = new ModerationRegistry();
		$registry->addModeration($moderation1);

		static::assertCount(1, $registry->getAll());

		$registry->addModeration($moderation2);

		static::assertCount(2, $registry->getAll());
	}

	public function testGetForContentReturnsNothingForUnsupportedContent()
	{
		$moderation1 = Mockery::mock('MyBB\Core\Moderation\ModerationInterface');
		$moderation1->shouldReceive('getKey')
			->withNoArgs()
			->andReturn('key1');
		$moderation1->shouldReceive('visible')
			->with('content')
			->andReturn(false);

		$moderation2 = Mockery::mock('MyBB\Core\Moderation\ModerationInterface');
		$moderation2->shouldReceive('getKey')
			->withNoArgs()
			->andReturn('key2');
		$moderation2->shouldReceive('visible')
			->with('content')
			->andReturn(false);

		$registry = new ModerationRegistry();
		$registry->addModeration($moderation1);
		$registry->addModeration($moderation2);

		$moderations = $registry->getForContent('content');
		static::assertCount(0, $moderations);
	}

	public function testGetForContentReturnsAllForAllSupportedContent()
	{
		$moderation1 = Mockery::mock('MyBB\Core\Moderation\ModerationInterface');
		$moderation1->shouldReceive('getKey')
			->withNoArgs()
			->andReturn('key1');
		$moderation1->shouldReceive('visible')
			->with('content')
			->andReturn(true);

		$moderation2 = Mockery::mock('MyBB\Core\Moderation\ModerationInterface');
		$moderation2->shouldReceive('getKey')
			->withNoArgs()
			->andReturn('key2');
		$moderation2->shouldReceive('visible')
			->with('content')
			->andReturn(true);

		$registry = new ModerationRegistry();
		$registry->addModeration($moderation1);
		$registry->addModeration($moderation2);

		$moderations = $registry->getForContent('content');
		static::assertCount(2, $moderations);
	}

	public function testGetForContentReturnsSomeForSomeSupportedContent()
	{
		$moderation1 = Mockery::mock('MyBB\Core\Moderation\ModerationInterface');
		$moderation1->shouldReceive('getKey')
			->withNoArgs()
			->andReturn('key1');
		$moderation1->shouldReceive('visible')
			->with('content')
			->andReturn(true);

		$moderation2 = Mockery::mock('MyBB\Core\Moderation\ModerationInterface');
		$moderation2->shouldReceive('getKey')
			->withNoArgs()
			->andReturn('key2');
		$moderation2->shouldReceive('visible')
			->with('content')
			->andReturn(false);

		$registry = new ModerationRegistry();
		$registry->addModeration($moderation1);
		$registry->addModeration($moderation2);

		$moderations = $registry->getForContent('content');
		static::assertCount(1, $moderations);
		static::assertContains($moderation1, $moderations);
	}
}
