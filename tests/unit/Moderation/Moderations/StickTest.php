<?php

namespace MyBB\Core\Moderation\Moderations;

use Mockery;
use MyBB\Core\Test\ClassAssertionsTrait;
use stdClass;

class StickTest extends \PHPUnit_Framework_TestCase
{
    use ClassAssertionsTrait;

    public function testCanBeConstructed()
    {
        $stick = new Stick();
        static::assertInstanceOf('MyBB\Core\Moderation\Moderations\Stick', $stick);
    }

    public function testCanStickStickableInterface()
    {
        $stickable = Mockery::mock('MyBB\Core\Moderation\Moderations\StickableInterface');
        $stickable->shouldReceive('stick')
            ->withNoArgs()
            ->once();

        $stick = new Stick();
        $stick->stick($stickable);
    }

    public function testCanUnstickStickableInterface()
    {
        $stickable = Mockery::mock('MyBB\Core\Moderation\Moderations\StickableInterface');
        $stickable->shouldReceive('unstick')
            ->withNoArgs()
            ->once();

        $stick = new Stick();
        $stick->unstick($stickable);
    }

    public function testHasKeyThatIsAString()
    {
        $stick = new stick();
        $key = $stick->getKey();
        static::assertInternalType('string', $key);
    }

    public function testSupportsStickableContent()
    {
        $stick = new Stick();
        static::assertTrue($stick->supports(Mockery::mock('MyBB\Core\Moderation\Moderations\StickableInterface')));
    }

    public function testDoesNotSupportInvalidContent()
    {
        $stick = new Stick();
        static::assertFalse($stick->supports(1));
        static::assertFalse($stick->supports('1'));
        static::assertFalse($stick->supports('foo'));
        static::assertFalse($stick->supports(new stdClass()));
    }

    public function testCanBeAppliedToSupportedContent()
    {
        $stickable = Mockery::mock('MyBB\Core\Moderation\Moderations\StickableInterface');
        $stickable->shouldReceive('stick')
            ->withNoArgs()
            ->once()
            ->andReturn(true);

        $stick = new Stick();
        static::assertNotNull($stick->apply($stickable));
    }

    public function testDoesNotApplyToUnsupportedContent()
    {
        $stick = new Stick();
        static::assertNull($stick->apply(1));
        static::assertNull($stick->apply('1'));
        static::assertNull($stick->apply('foo'));
        static::assertNull($stick->apply(new stdClass()));
    }

    public function testCanBeReversedForSupportedContent()
    {
        $stickable = Mockery::mock('MyBB\Core\Moderation\Moderations\StickableInterface');
        $stickable->shouldReceive('unstick')
            ->withNoArgs()
            ->once()
            ->andReturn(true);

        $stick = new Stick();
        static::assertNotNull($stick->reverse($stickable));
    }

    public function testDoesNotReverseForUnsupportedContent()
    {
        $stick = new Stick();
        static::assertNull($stick->reverse(1));
        static::assertNull($stick->reverse('1'));
        static::assertNull($stick->reverse('foo'));
        static::assertNull($stick->reverse(new stdClass()));
    }

    public function testIsVisibleForValidContent()
    {
        $stick = new Stick();
        static::assertTrue($stick->visible(Mockery::mock('MyBB\Core\Moderation\Moderations\StickableInterface')));
    }

    public function testIsNotVisibleForInvalidContent()
    {
        $stick = new Stick();
        static::assertFalse($stick->supports(1));
        static::assertFalse($stick->supports('1'));
        static::assertFalse($stick->supports('foo'));
        static::assertFalse($stick->supports(new stdClass()));
    }

    public function testCanGetNameAsString()
    {
        $stick = new Stick();

        static::assertInternalType('string', $stick->getName());
    }

    public function testCanGetReverseNameAsString()
    {
        $stick = new Stick();

        static::assertInternalType('string', $stick->getReverseName());
    }

    public function testCanGetPresenterClassAsString()
    {
        $stick = new Stick();

        static::assertInternalType('string', $stick->getPresenterClass());
    }

    public function testCanGetValidPresenterClassName()
    {
        $stick = new Stick();

        static::assertClassExtends($stick->getPresenterClass(), 'McCool\LaravelAutoPresenter\BasePresenter');
    }

    public function testCanGetPermissionNameAsString()
    {
        $stick = new Stick();

        static::assertInternalType('string', $stick->getPermissionName());
    }
}
