<?php

namespace MyBB\Core\Moderation\Moderations;

use Mockery;
use MyBB\Core\Test\ClassAssertionsTrait;
use stdClass;

class ApproveTest extends \PHPUnit_Framework_TestCase
{
    use ClassAssertionsTrait;

    public function testCanBeConstructed()
    {
        $approve = new Approve();
        static::assertInstanceOf('MyBB\Core\Moderation\Moderations\Approve', $approve);
    }

    public function testCanApproveApprovableInterface()
    {
        $approvable = Mockery::mock('MyBB\Core\Moderation\Moderations\ApprovableInterface');
        $approvable->shouldReceive('approve')
            ->withNoArgs()
            ->once();

        $approve = new Approve();
        $approve->approve($approvable);
    }

    public function testCanUnapproveApprovableInterface()
    {
        $approvable = Mockery::mock('MyBB\Core\Moderation\Moderations\ApprovableInterface');
        $approvable->shouldReceive('unapprove')
            ->withNoArgs()
            ->once();

        $approve = new Approve();
        $approve->unapprove($approvable);
    }

    public function testHasKeyThatIsAString()
    {
        $approve = new Approve();
        $key = $approve->getKey();
        static::assertInternalType('string', $key);
    }

    public function testSupportsApprovableContent()
    {
        $approve = new Approve();
        static::assertTrue($approve->supports(Mockery::mock('MyBB\Core\Moderation\Moderations\ApprovableInterface')));
    }

    public function testDoesNotSupportInvalidContent()
    {
        $approve = new Approve();
        static::assertFalse($approve->supports(1));
        static::assertFalse($approve->supports('1'));
        static::assertFalse($approve->supports('foo'));
        static::assertFalse($approve->supports(new stdClass()));
    }

    public function testCanBeAppliedToSupportedContent()
    {
        $approvable = Mockery::mock('MyBB\Core\Moderation\Moderations\ApprovableInterface');
        $approvable->shouldReceive('approve')
            ->withNoArgs()
            ->once()
            ->andReturn(true);

        $approve = new Approve();
        static::assertNotNull($approve->apply($approvable));
    }

    public function testDoesNotApplyToUnsupportedContent()
    {
        $approve = new Approve();
        static::assertNull($approve->apply(1));
        static::assertNull($approve->apply('1'));
        static::assertNull($approve->apply('foo'));
        static::assertNull($approve->apply(new stdClass()));
    }

    public function testCanBeReversedForSupportedContent()
    {
        $approvable = Mockery::mock('MyBB\Core\Moderation\Moderations\ApprovableInterface');
        $approvable->shouldReceive('unapprove')
            ->withNoArgs()
            ->once()
            ->andReturn(true);

        $approve = new Approve();
        static::assertNotNull($approve->reverse($approvable));
    }

    public function testDoesNotReverseForUnsupportedContent()
    {
        $approve = new Approve();
        static::assertNull($approve->reverse(1));
        static::assertNull($approve->reverse('1'));
        static::assertNull($approve->reverse('foo'));
        static::assertNull($approve->reverse(new stdClass()));
    }

    public function testIsVisibleForValidContent()
    {
        $approve = new Approve();
        static::assertTrue($approve->visible(Mockery::mock('MyBB\Core\Moderation\Moderations\ApprovableInterface')));
    }

    public function testIsNotVisibleForInvalidContent()
    {
        $approve = new Approve();
        static::assertFalse($approve->supports(1));
        static::assertFalse($approve->supports('1'));
        static::assertFalse($approve->supports('foo'));
        static::assertFalse($approve->supports(new stdClass()));
    }

    public function testCanGetNameAsString()
    {
        $approve = new Approve();

        static::assertInternalType('string', $approve->getName());
    }

    public function testCanGetReverseNameAsString()
    {
        $approve = new Approve();

        static::assertInternalType('string', $approve->getReverseName());
    }

    public function testCanGetPermissionNameAsString()
    {
        $approve = new Approve();

        static::assertInternalType('string', $approve->getPermissionName());
    }

    public function testCanGetPresenterClassAsString()
    {
        $approve = new Approve();

        static::assertInternalType('string', $approve->getPresenterClass());
    }

    public function testCanGetValidPresenterClassName()
    {
        $approve = new Approve();

        static::assertClassExtends($approve->getPresenterClass(), 'McCool\LaravelAutoPresenter\BasePresenter');
    }
}
