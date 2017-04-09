<?php

namespace MyBB\Core\Tasking;

use Mockery;

class AbstractTaskTest extends \PHPUnit_Framework_TestCase
{
    public function testCanBeFired()
    {
        $task = Mockery::mock(AbstractTask::class);
        $taskModel = Mockery::mock(\MyBB\Core\Database\Models\Task::class);

        $task->shouldReceive('fire')
            ->with($taskModel)
            ->andReturnNull();
    }

    public function testIsCommand()
    {
        $task = Mockery::mock(AbstractTask::class);
        static::assertInstanceOf(\Illuminate\Console\Command::class, $task);
    }
}
