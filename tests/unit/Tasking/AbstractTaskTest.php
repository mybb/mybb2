<?php

namespace MyBB\Core\Tasking;

use Mockery;

class AbstractTaskTest extends \PHPUnit_Framework_TestCase
{
    public function testCanBeFired()
    {
        $task = Mockery::mock('MyBB\Core\Tasking\AbstractTask');
        $taskModel = Mockery::mock('MyBB\Core\Database\Models\Task');

        $task->shouldReceive('fire')
            ->with($taskModel)
            ->andReturnNull();
    }

    public function testIsCommand()
    {
        $task = Mockery::mock('MyBB\Core\Tasking\AbstractTask');
        static::assertInstanceOf('Illuminate\Console\Command', $task);
    }
}
