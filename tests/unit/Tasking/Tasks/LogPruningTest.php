<?php

namespace MyBB\Core\Tasking\Tasks;

use Mockery;
use MyBB\Core\Tasking\AbstractTask;

class LogPruningTest extends \PHPUnit_Framework_TestCase
{
    public function testCanBeConstructed()
    {
        $tasksRepository = Mockery::mock(\MyBB\Core\Database\Repositories\TasksRepositoryInterface::class);
        $settings = Mockery::mock(\MyBB\Settings\Store::class);
        $moderationLog = Mockery::mock(\MyBB\Core\Database\Repositories\ModerationLogRepositoryInterface::class);

        $task = new LogPruningTask($settings, $tasksRepository, $moderationLog);

        static::assertInstanceOf(LogPruningTask::class, $task);
        static::assertInstanceOf(AbstractTask::class, $task);
    }

    public function testHasName()
    {
        $task = Mockery::mock(LogPruningTask::class);
        static::assertInternalType('string', $this->getProtectedProperty($task, 'name'));
    }

    public function testHasDescription()
    {
        $task = Mockery::mock(LogPruningTask::class);
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
