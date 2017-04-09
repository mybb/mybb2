<?php

namespace MyBB\Core\Database\Repositories\Eloquent;

use Mockery;
use Illuminate\Support\Collection;
use MyBB\Core\Database\Models\Task;
use MyBB\Core\Database\Models\TaskLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;

class TasksRepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCanBeConstructed()
    {
        $task = Mockery::mock(Task::class);
        $taskLog = Mockery::mock(TaskLog::class);
        $repository = new TasksRepository($task, $taskLog);

        static::assertInstanceOf(TasksRepository::class, $repository);
    }

    public function testCanRetrieveAllTasks()
    {
        $collection = Mockery::mock(Collection::class);
        $task = Mockery::mock(Task::class);
        $taskLog = Mockery::mock(TaskLog::class);

        $task->shouldReceive('all')
            ->withNoArgs()
            ->andReturn($collection);

        $repository = new TasksRepository($task, $taskLog);

        static::assertEquals($collection, $repository->all());
    }

    public function testCanRetrieveOneTask()
    {
        $task = Mockery::mock(Task::class);
        $taskLog = Mockery::mock(TaskLog::class);

        $task->shouldReceive('find')
            ->with(1)
            ->andReturn($task);

        $repository = new TasksRepository($task, $taskLog);

        static::assertEquals($task, $repository->find(1));
    }

    public function testCanUpdateTask()
    {
        $task = Mockery::mock(Task::class);
        $taskLog = Mockery::mock(TaskLog::class);
        $details = ['foo' => 'bar'];

        $task->shouldReceive('update')
            ->with($task)
            ->with($details)
            ->andReturn(true);

        $repository = new TasksRepository($task, $taskLog);

        static::assertTrue($repository->update($task, $details));
    }

    public function testCanCreateTask()
    {
        $task = Mockery::mock(Task::class);
        $taskLog = Mockery::mock(TaskLog::class);
        $details = ['foo' => 'bar'];

        $task->shouldReceive('create')
            ->with($details)
            ->andReturn($task);

        $repository = new TasksRepository($task, $taskLog);

        static::assertEquals($task, $repository->createTask($details));
    }

    public function testCanDeleteTaskWithLogs()
    {
        $task = Mockery::mock(Task::class);
        $taskLog = Mockery::mock(TaskLog::class);

        $task->shouldReceive('logs')
            ->withNoArgs()
            ->andReturn($taskLog);

        $taskLog->shouldReceive('detach')
            ->withNoArgs()
            ->andReturn(true);

        $task->shouldReceive('delete')
            ->withNoArgs()
            ->andReturn(true);

        $repository = new TasksRepository($task, $taskLog);

        static::assertTrue($repository->deleteWithLogs($task));
    }

    public function testRetrieveEnabledTasks()
    {
        $collection = Mockery::mock(Collection::class);
        $task = Mockery::mock(Task::class);
        $taskLog = Mockery::mock(TaskLog::class);
        $builder = Mockery::mock(Builder::class);

        $builder->shouldReceive('get')
            ->withNoArgs()
            ->andReturn($collection);

        $task->shouldReceive('where')
            ->with('enabled', 1)
            ->andReturn($builder);

        $repository = new TasksRepository($task, $taskLog);

        static::assertEquals($collection, $repository->getEnabledTasks());
    }

    public function testRetrieveTaskToRun()
    {
        $task = Mockery::mock(Task::class);
        $taskLog = Mockery::mock(TaskLog::class);
        $builder = Mockery::mock(Builder::class);

        $builder->shouldReceive('first')
            ->withNoArgs()
            ->andReturn($task);

        $builder->shouldReceive('where')
            ->with('next_run', '<', time())
            ->andReturn($builder);

        $task->shouldReceive('where')
            ->with('enabled', 1)
            ->andReturn($builder);

        $repository = new TasksRepository($task, $taskLog);

        static::assertEquals($task, $repository->getTaskToRun());
    }

    public function testRetrieveTasksToRun()
    {
        $collection = Mockery::mock(Collection::class);
        $task = Mockery::mock(Task::class);
        $taskLog = Mockery::mock(TaskLog::class);
        $builder = Mockery::mock(Builder::class);

        $builder->shouldReceive('get')
            ->withNoArgs()
            ->andReturn($collection);

        $builder->shouldReceive('where')
            ->with('next_run', '<', time())
            ->andReturn($builder);

        $task->shouldReceive('where')
            ->with('enabled', 1)
            ->andReturn($builder);

        $repository = new TasksRepository($task, $taskLog);

        static::assertEquals($collection, $repository->getTasksToRun());
    }

    public function testCanSetTaskAsExecuted()
    {
        $task = Mockery::mock(Task::class);
        $taskLog = Mockery::mock(TaskLog::class);
        $cron = Mockery::mock('Cron\CronExpression');

        $cron->shouldReceive('factory')
            ->with('* * * * *')
            ->andReturnSelf();

        $cron->shouldReceive('getNextRunDate')
            ->withNoArgs()
            ->andReturn(new \DateTime());

        $cron->shouldReceive('getTimestamp')
            ->withNoArgs()
            ->andReturn(time() + 60);

        $task->shouldReceive('getAttribute')
            ->with('frequency')
            ->andReturn('* * * * *');

        $task->shouldReceive('update')
            ->withAnyArgs()
            ->andReturn(true);

        $repository = new TasksRepository($task, $taskLog);

        static::assertTrue($repository->setTaskAsExecuted($task));
    }

    public function testRetrieveNumberOfTaskToRun()
    {
        $task = Mockery::mock(Task::class);
        $taskLog = Mockery::mock(TaskLog::class);
        $builder = Mockery::mock(Builder::class);

        $builder->shouldReceive('count')
            ->withNoArgs()
            ->andReturn(1);

        $task->shouldReceive('where')
            ->with('next_run', '<', time())
            ->andReturn($builder);

        $repository = new TasksRepository($task, $taskLog);

        static::assertEquals(1, $repository->getNumberOfTasksToRun());
    }

    public function testCanCreateLog()
    {
        $task = Mockery::mock(Task::class);
        $taskLog = Mockery::mock(TaskLog::class);

        $task->shouldReceive('getAttribute')
            ->with('logging')
            ->andReturn(true);

        $task->shouldReceive('logs')
            ->withNoArgs()
            ->andReturn($taskLog);

        $taskLog->shouldReceive('create')
            ->withAnyArgs()
            ->andReturn(true);

        $repository = new TasksRepository($task, $taskLog);

        static::assertTrue($repository->createLog($task, 'Foo'));
    }

    public function testCanRetrieveLogsForSpecifiedTask()
    {
        $task = Mockery::mock(Task::class);
        $taskLog = Mockery::mock(TaskLog::class);
        $builder = Mockery::mock(Builder::class);
        $paginator = Mockery::mock(Paginator::class);

        $taskLog->shouldReceive('where')
            ->with('task_id', 1)
            ->andReturn($builder);

        $builder->shouldReceive('orderBy')
            ->with('created_at', 'desc')
            ->andReturn($builder);

        $builder->shouldReceive('with')
            ->with(['task'])
            ->andReturn($builder);

        $builder->shouldReceive('simplePaginate')
            ->with(50)
            ->andReturn($paginator);

        $repository = new TasksRepository($task, $taskLog);

        static::assertEquals($paginator, $repository->getLogsForTask(1));
    }

    public function testCanRetrieveLogsTasks()
    {
        $task = Mockery::mock(Task::class);
        $taskLog = Mockery::mock(TaskLog::class);
        $builder = Mockery::mock(Builder::class);
        $paginator = Mockery::mock(Paginator::class);

        $taskLog->shouldReceive('orderBy')
            ->with('created_at', 'desc')
            ->andReturn($builder);

        $builder->shouldReceive('with')
            ->with(['task'])
            ->andReturn($builder);

        $builder->shouldReceive('simplePaginate')
            ->with(50)
            ->andReturn($paginator);

        $repository = new TasksRepository($task, $taskLog);

        static::assertEquals($paginator, $repository->getLogs());
    }

    public function testCanDeleteLogsOlderThanTimestamp()
    {
        $task = Mockery::mock(Task::class);
        $taskLog = Mockery::mock(TaskLog::class);
        $builder = Mockery::mock(Builder::class);

        $taskLog->shouldReceive('where')
            ->with('created_at', '<', '2017-01-01 00:00')
            ->andReturn($builder);

        $builder->shouldReceive('delete')
            ->withNoArgs()
            ->andReturn(true);

        $repository = new TasksRepository($task, $taskLog);

        static::assertTrue($repository->deleteLogsOlderThan('2017-01-01 00:00'));
    }
}
