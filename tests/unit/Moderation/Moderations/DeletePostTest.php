<?php

namespace MyBB\Core\Moderation\Moderations;

use Mockery;
use MyBB\Core\Test\ClassAssertionsTrait;

class DeletePostTest extends \PHPUnit_Framework_TestCase
{
	use ClassAssertionsTrait;

	public function testCanBeConstructed()
	{
		$postRepository = Mockery::mock('MyBB\Core\Database\Repositories\PostRepositoryInterface');
		$deletePost = new DeletePost($postRepository);
		static::assertInstanceOf('MyBB\Core\Moderation\Moderations\DeletePost', $deletePost);
	}

	public function testHasKeyThatIsAString()
	{
		$postRepository = Mockery::mock('MyBB\Core\Database\Repositories\PostRepositoryInterface');
		$deletePost = new DeletePost($postRepository);
		static::assertInternalType('string', $deletePost->getKey());
	}

	public function testCanDeletePost()
	{
		$post = Mockery::mock('MyBB\Core\Database\Models\Post');
		$postRepository = Mockery::mock('MyBB\Core\Database\Repositories\PostRepositoryInterface');
		$postRepository->shouldReceive('deletePost')
			->with($post)
			->once();

		$deletePost = new DeletePost($postRepository);
		$deletePost->deletePost($post);
	}

	public function testIsSupportedForValidContent()
	{
		$post = Mockery::mock('MyBB\Core\Database\Models\Post');
		$postRepository = Mockery::mock('MyBB\Core\Database\Repositories\PostRepositoryInterface');
		$deletePost = new DeletePost($postRepository);
		static::assertTrue($deletePost->supports($post));
	}

	public function testDoesNotSupportInvalidContent()
	{
		$postRepository = Mockery::mock('MyBB\Core\Database\Repositories\PostRepositoryInterface');
		$deletePost = new DeletePost($postRepository);

		static::assertFalse($deletePost->supports(1));
		static::assertFalse($deletePost->supports('1'));
		static::assertFalse($deletePost->supports('foo'));
		static::assertFalse($deletePost->supports([]));
		static::assertFalse($deletePost->supports(new \stdClass()));
	}

	public function testCanBeAppliedToSupportedContent()
	{
		$post = Mockery::mock('MyBB\Core\Database\Models\Post');
		$postRepository = Mockery::mock('MyBB\Core\Database\Repositories\PostRepositoryInterface');
		$postRepository->shouldReceive('deletePost')
			->with($post)
			->once();

		$deletePost = new DeletePost($postRepository);
		static::assertNull($deletePost->apply($post));
	}

	public function testIsNotAppliedToUnsupportedContent()
	{
		$postRepository = Mockery::mock('MyBB\Core\Database\Repositories\PostRepositoryInterface');
		$postRepository->shouldReceive('deletePost')
			->never();

		$deletePost = new DeletePost($postRepository);

		static::assertNull($deletePost->apply(1));
		static::assertNull($deletePost->apply('1'));
		static::assertNull($deletePost->apply('foo'));
		static::assertNull($deletePost->apply([]));
		static::assertNull($deletePost->apply(new \stdClass()));
	}

	public function testIsVisibleForValidContent()
	{
		$postRepository = Mockery::mock('MyBB\Core\Database\Repositories\PostRepositoryInterface');
		$deletePost = new DeletePost($postRepository);

		static::assertTrue($deletePost->visible(Mockery::mock('MyBB\Core\Database\Models\Post')));
	}

	public function testIsNotVisibleForInvalidContent()
	{
		$postRepository = Mockery::mock('MyBB\Core\Database\Repositories\PostRepositoryInterface');
		$deletePost = new DeletePost($postRepository);

		static::assertFalse($deletePost->supports(1));
		static::assertFalse($deletePost->supports('1'));
		static::assertFalse($deletePost->supports('foo'));
		static::assertFalse($deletePost->supports(new \stdClass()));
	}

	public function testCanGetNameAsString()
	{
		$postRepository = Mockery::mock('MyBB\Core\Database\Repositories\PostRepositoryInterface');
		$deletePost = new DeletePost($postRepository);

		static::assertInternalType('string', $deletePost->getName());
	}

	public function testCanGetPresenterClassAsString()
	{
		$postRepository = Mockery::mock('MyBB\Core\Database\Repositories\PostRepositoryInterface');
		$deletePost = new DeletePost($postRepository);

		static::assertInternalType('string', $deletePost->getPresenterClass());
	}

	public function testCanGetValidPresenterClassName()
	{
		$postRepository = Mockery::mock('MyBB\Core\Database\Repositories\PostRepositoryInterface');
		$deletePost = new DeletePost($postRepository);

		static::assertClassExtends($deletePost->getPresenterClass(), 'McCool\LaravelAutoPresenter\BasePresenter');
	}
}
