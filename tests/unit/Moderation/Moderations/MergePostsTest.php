<?php

namespace MyBB\Core\Moderation\Moderations;

use Mockery;
use MyBB\Core\Test\ClassAssertionsTrait;

class MergePostsTest extends \PHPUnit_Framework_TestCase
{
	use ClassAssertionsTrait;

	public function testCanBeConstructed()
	{
		$postRepository = Mockery::mock('MyBB\Core\Database\Repositories\PostRepositoryInterface');
		$mergePosts = new MergePosts($postRepository);
		static::assertInstanceOf('MyBB\Core\Moderation\Moderations\MergePosts', $mergePosts);
	}

	public function testHasKeyThatIsAString()
	{
		$postRepository = Mockery::mock('MyBB\Core\Database\Repositories\PostRepositoryInterface');
		$mergePosts = new MergePosts($postRepository);
		static::assertInternalType('string', $mergePosts->getKey());
	}

	public function testCanMergePosts()
	{
		$post1 = Mockery::mock('MyBB\Core\Database\Models\Post');
		$post2 = Mockery::mock('MyBB\Core\Database\Models\Post');

		$postRepository = Mockery::mock('MyBB\Core\Database\Repositories\PostRepositoryInterface');
		$postRepository->shouldReceive('mergePosts')
			->with([$post1, $post2])
			->once();

		$mergePosts = new MergePosts($postRepository);
		$mergePosts->merge([$post1, $post2]);
	}

	public function testIsSupportedForValidContent()
	{
		$post = Mockery::mock('MyBB\Core\Database\Models\Post');
		$postRepository = Mockery::mock('MyBB\Core\Database\Repositories\PostRepositoryInterface');
		$mergePosts = new MergePosts($postRepository);
		static::assertTrue($mergePosts->supports([$post]));
	}

	public function testDoesNotSupportInvalidContent()
	{
		$postRepository = Mockery::mock('MyBB\Core\Database\Repositories\PostRepositoryInterface');
		$mergePosts = new MergePosts($postRepository);

		static::assertFalse($mergePosts->supports(1));
		static::assertFalse($mergePosts->supports('1'));
		static::assertFalse($mergePosts->supports('foo'));
		static::assertFalse($mergePosts->supports([]));
		static::assertFalse($mergePosts->supports(new \stdClass()));
	}

	public function testCanBeAppliedToSupportedContent()
	{
		$post1 = Mockery::mock('MyBB\Core\Database\Models\Post');
		$post2 = Mockery::mock('MyBB\Core\Database\Models\Post');
		$postRepository = Mockery::mock('MyBB\Core\Database\Repositories\PostRepositoryInterface');
		$postRepository->shouldReceive('mergePosts')
			->with([$post1, $post2])
			->once();

		$mergePosts = new MergePosts($postRepository);
		static::assertNull($mergePosts->apply([$post1, $post2]));
	}

	public function testIsNotAppliedToUnsupportedContent()
	{
		$postRepository = Mockery::mock('MyBB\Core\Database\Repositories\PostRepositoryInterface');
		$mergePosts = new MergePosts($postRepository);

		static::assertNull($mergePosts->apply(1));
		static::assertNull($mergePosts->apply('1'));
		static::assertNull($mergePosts->apply('foo'));
		static::assertNull($mergePosts->apply([]));
		static::assertNull($mergePosts->apply(new \stdClass()));
	}

	public function testIsVisibleForValidContent()
	{
		$postRepository = Mockery::mock('MyBB\Core\Database\Repositories\PostRepositoryInterface');
		$mergePosts = new MergePosts($postRepository);

		static::assertTrue($mergePosts->visible(Mockery::mock('MyBB\Core\Database\Models\Post')));
	}

	public function testIsNotVisibleForInvalidContent()
	{
		$postRepository = Mockery::mock('MyBB\Core\Database\Repositories\PostRepositoryInterface');
		$mergePosts = new MergePosts($postRepository);

		static::assertFalse($mergePosts->supports(1));
		static::assertFalse($mergePosts->supports('1'));
		static::assertFalse($mergePosts->supports('foo'));
		static::assertFalse($mergePosts->supports(new \stdClass()));
	}

	public function testCanGetNameAsString()
	{
		$postRepository = Mockery::mock('MyBB\Core\Database\Repositories\PostRepositoryInterface');
		$mergePosts = new MergePosts($postRepository);

		static::assertInternalType('string', $mergePosts->getName());
	}

	public function testCanGetPresenterClassAsString()
	{
		$postRepository = Mockery::mock('MyBB\Core\Database\Repositories\PostRepositoryInterface');
		$mergePosts = new MergePosts($postRepository);

		static::assertInternalType('string', $mergePosts->getPresenterClass());
	}

	public function testCanGetValidPresenterClassName()
	{
		$postRepository = Mockery::mock('MyBB\Core\Database\Repositories\PostRepositoryInterface');
		$mergePosts = new MergePosts($postRepository);

		static::assertClassExtends($mergePosts->getPresenterClass(), 'McCool\LaravelAutoPresenter\BasePresenter');
	}

	public function testCanGetPermissionNameAsString()
	{
		$postRepository = Mockery::mock('MyBB\Core\Database\Repositories\PostRepositoryInterface');
		$mergePosts = new MergePosts($postRepository);

		static::assertInternalType('string', $mergePosts->getPermissionName());
	}
}
