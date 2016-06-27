<?php

namespace MyBB\Core\Moderation\Moderations;

use Mockery;
use MyBB\Core\Test\ClassAssertionsTrait;
use stdClass;

class CloseTest extends \PHPUnit_Framework_TestCase
{
    use ClassAssertionsTrait;

    public function testCanBeConstructed()
    {
        $close = new Close();
        static::assertInstanceOf('MyBB\Core\Moderation\Moderations\Close', $close);
    }

    public function testCanCloseClosableInterface()
    {
        $closeable = Mockery::mock('MyBB\Core\Moderation\Moderations\CloseableInterface');
        $closeable->shouldReceive('close')
            ->withNoArgs()
            ->once();

        $close = new Close();
        $close->close($closeable);
    }

    public function testCanOpenClosableInterface()
    {
        $closeable = Mockery::mock('MyBB\Core\Moderation\Moderations\CloseableInterface');
        $closeable->shouldReceive('open')
            ->withNoArgs()
            ->once();

        $close = new Close();
        $close->open($closeable);
    }

    public function testHasKeyThatIsAString()
    {
        $close = new Close();
        $key = $close->getKey();
        static::assertInternalType('string', $key);
    }

    public function testSupportsCloseableContent()
    {
        $close = new Close();
        static::assertTrue($close->supports(Mockery::mock('MyBB\Core\Moderation\Moderations\CloseableInterface')));
    }

    public function testDoesNotSupportInvalidContent()
    {
        $close = new Close();
        static::assertFalse($close->supports(1));
        static::assertFalse($close->supports('1'));
        static::assertFalse($close->supports('foo'));
        static::assertFalse($close->supports(new stdClass()));
    }

    public function testCanBeAppliedToSupportedContent()
    {
        $closeable = Mockery::mock('MyBB\Core\Moderation\Moderations\CloseableInterface');
        $closeable->shouldReceive('close')
            ->withNoArgs()
            ->once()
            ->andReturn(true);

        $close = new Close();
        static::assertNotNull($close->apply($closeable));
    }

    public function testDoesNotApplyToUnsupportedContent()
    {
        $close = new Close();
        static::assertNull($close->apply(1));
        static::assertNull($close->apply('1'));
        static::assertNull($close->apply('foo'));
        static::assertNull($close->apply(new stdClass()));
    }

    public function testCanBeReversedForSupportedContent()
    {
        $closeable = Mockery::mock('MyBB\Core\Moderation\Moderations\CloseableInterface');
        $closeable->shouldReceive('open')
            ->withNoArgs()
            ->once()
            ->andReturn(true);

        $close = new Close();
        static::assertNotNull($close->reverse($closeable));
    }

    public function testDoesNotReverseForUnsupportedContent()
    {
        $close = new Close();
        static::assertNull($close->reverse(1));
        static::assertNull($close->reverse('1'));
        static::assertNull($close->reverse('foo'));
        static::assertNull($close->reverse(new stdClass()));
    }

    public function testIsVisibleForValidContent()
    {
        $close = new Close();
        static::assertTrue($close->visible(Mockery::mock('MyBB\Core\Moderation\Moderations\CloseableInterface')));
    }

    public function testIsNotVisibleForInvalidContent()
    {
        $close = new Close();
        static::assertFalse($close->supports(1));
        static::assertFalse($close->supports('1'));
        static::assertFalse($close->supports('foo'));
        static::assertFalse($close->supports(new stdClass()));
    }

    public function testCanGetNameAsString()
    {
        $close = new Close();

        static::assertInternalType('string', $close->getName());
    }

    public function testCanGetReverseNameAsString()
    {
        $close = new Close();

        static::assertInternalType('string', $close->getReverseName());
    }

    public function testCanGetPresenterClassAsString()
    {
        $close = new Close();

        static::assertInternalType('string', $close->getPresenterClass());
    }

    public function testCanGetValidPresenterClassName()
    {
        $close = new Close();

        static::assertClassExtends($close->getPresenterClass(), 'McCool\LaravelAutoPresenter\BasePresenter');
    }

    public function testCanGetPermissionNameAsString()
    {
        $close = new Close();

        static::assertInternalType('string', $close->getPermissionName());
    }
}
