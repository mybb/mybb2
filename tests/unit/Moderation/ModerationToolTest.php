<?php

namespace MyBB\Core\Moderation;

use Mockery;
use PHPUnit_Framework_Error;

class ModerationToolTest extends \PHPUnit_Framework_TestCase
{
    public function testCanBeMinimallyConstructed()
    {
        $tool = new ModerationTool('name', 'description');
        static::assertInstanceOf('MyBB\Core\Moderation\ModerationTool', $tool);
    }

    public function testCanBeConstructedWithModerations()
    {
        $moderation = Mockery::mock('MyBB\Core\Moderation\ModerationInterface');
        $moderation->shouldReceive('getName')
            ->withNoArgs()
            ->andReturn('name');

        $tool = new ModerationTool('name', 'description', null, [$moderation]);
        static::assertInstanceOf('MyBB\Core\Moderation\ModerationTool', $tool);
    }

    public function testModerationCanBeAdded()
    {
        $moderation = Mockery::mock('MyBB\Core\Moderation\ModerationInterface');
        $moderation->shouldReceive('getName')
            ->withNoArgs()
            ->andReturn('name');

        $tool = new ModerationTool('name', 'description');
        $tool->addModeration($moderation);
    }

    public function testGetters()
    {
        $tool = new ModerationTool('name', 'description');

        $expectations = [
            'getName'        => 'name',
            'getDescription' => 'description',
        ];

        foreach ($expectations as $method => $value) {
            static::assertEquals($tool->{$method}(), $value);
        }
    }

    public function testSupportsReturnsBoolean()
    {
        $tool = new ModerationTool('name', 'description');
        static::assertInternalType('bool', $tool->supports('content'));
    }

    public function testApplyCallsApplyOnAllModerations()
    {
        $moderation1 = Mockery::mock('MyBB\Core\Moderation\ModerationInterface');
        $moderation1->shouldReceive('getName')
            ->withNoArgs()
            ->andReturn('name1');
        $moderation1->shouldReceive('apply')
            ->with('content')
            ->once();

        $moderation2 = Mockery::mock('MyBB\Core\Moderation\ModerationInterface');
        $moderation2->shouldReceive('getName')
            ->withNoArgs()
            ->andReturn('name2');
        $moderation2->shouldReceive('apply')
            ->with('content')
            ->once();

        $tool = new ModerationTool('name', 'description', null, [$moderation1]);
        $tool->addModeration($moderation2);
        $tool->apply('content');
    }

    public function testGetKeyReturnsAString()
    {
        $tool = new ModerationTool('name', 'description');
        static::assertInternalType('string', $tool->getKey());
    }

    public function testVisibleReturnsBoolean()
    {
        $tool = new ModerationTool('name', 'description');
        static::assertInternalType('bool', $tool->visible('content'));
    }

    public function testCanGetPermissionName()
    {
        $tool = new ModerationTool('name', 'description', 'permission');
        static::assertInternalType('string', $tool->getPermissionName());
    }
}
