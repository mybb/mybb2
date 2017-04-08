<?php

namespace MyBB\Core\Tasking\Tasks;

use Mockery;

class LogPruningTest extends \PHPUnit_Framework_TestCase
{
    public function testCanBeConstructed()
    {
        $tasksRepository = Mockery::mock('MyBB\Core\Database\Repositories\TasksRepositoryInterface');
        $settings = Mockery::mock('MyBB\Settings\Store');
        $moderationLog = Mockery::mock('MyBB\Core\Database\Repositories\ModerationLogRepositoryInterface');

        $task = new LogPruningTask($settings, $tasksRepository, $moderationLog);

        static::assertInstanceOf('MyBB\Core\Tasking\Tasks\LogPruningTask', $task);
        static::assertInstanceOf('MyBB\Core\Tasking\AbstractTask', $task);
    }

    public function testHasName()
    {
        $task = Mockery::mock('MyBB\Core\Tasking\Tasks\LogPruningTask');
        static::assertInternalType('string', $this->getProtectedProperty($task, 'name'));
    }

    public function testHasDescription()
    {
        $task = Mockery::mock('MyBB\Core\Tasking\Tasks\LogPruningTask');
        static::assertInternalType('string', $this->getProtectedProperty($task, 'description'));
    }

    private function getProtectedProperty($instance, $name)
    {
        $reflection = new \ReflectionClass($instance);
        $property = $reflection->getProperty($name);
        $property->setAccessible(true);
        return $property->getValue($instance);
    }
}
