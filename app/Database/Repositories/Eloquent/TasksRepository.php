<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories\Eloquent;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use MyBB\Core\Database\Models\Task;
use MyBB\Core\Database\Models\TaskLog;
use MyBB\Core\Database\Repositories\TasksRepositoryInterface;
use Cron\CronExpression;

class TasksRepository implements TasksRepositoryInterface
{

    /**
     * @var Task
     */
    protected $taskModel;

    /**
     * @var TaskLog
     */
    protected $taskLogsModel;

    /**
     * TasksRepository constructor.
     *
     * @param Task $taskModel
     * @param TaskLog $taskLogsModel
     */
    public function __construct(Task $taskModel, TaskLog $taskLogsModel)
    {
        $this->taskModel = $taskModel;
        $this->taskLogsModel = $taskLogsModel;
    }

    /**
     * Get all tasks
     *
     * @return Collection
     */
    public function all() : Collection
    {
        return $this->taskModel->all();
    }

    /**
     * Find one task by id
     *
     * @param int $id Task id
     * @return Task
     */
    public function find(int $id) : Task
    {
        return $this->taskModel->find($id);
    }

    /**
     * Update Task
     *
     * @param Task $task Task Model
     * @param array $options Tables to update
     * @return bool
     */
    public function update(Task $task, array $options = []) : bool
    {
        return $task->update($options);
    }

    /**
     * Create new Task
     *
     * @param array $options
     * @return Task
     */
    public function createTask(array $options = []) : Task
    {
        return $this->taskModel->create($options);
    }

    /**
     * Delete task with all related logs
     *
     * @param Task $task
     * @return bool|null
     */
    public function deleteWithLogs(Task $task)
    {
        $task->logs()->detach();
        return $task->delete();
    }

    /**
     * Get all enabled tasks
     *
     * @return Collection
     */
    public function getEnabledTasks() : Collection
    {
        return $this->taskModel->where('enabled', 1)->get();
    }

    /**
     * Get first task to run
     *
     * @return  Task|null
     */
    public function getTaskToRun()
    {
        return $this->taskModel->where('enabled', 1)->where('next_run', '<', time())->first();
    }

    /**
     * Get all task that need be run
     *
     * @return Collection
     */
    public function getTasksToRun() : Collection
    {
        return $this->taskModel->where('enabled', 1)->where('next_run', '<', time())->get();
    }

    /**
     * Set task execution timestamps
     *
     * @param Task $task Task Model
     * @return bool
     */
    public function setTaskAsExecuted(Task $task) : bool
    {
        $cron = CronExpression::factory($task->frequency);
        return $task->update([
            'last_run' => time(),
            'next_run' => $cron->getNextRunDate()->getTimestamp(),
        ]);
    }

    /**
     * Get task number that need to be run
     *
     * @return int Number of tasks
     */
    public function getNumberOfTasksToRun() : int
    {
        return $this->taskModel->where('next_run', '<', time())->count();
    }

    /**
     * Create task log
     *
     * @param Task $task Task Model
     * @param string $content Log content
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createLog(Task $task, string $content)
    {
        if ($task->logging) {
            return $task->logs()->create(['content' => $content]);
        }
    }

    /**
     * Get all logs for specified task
     *
     * @param int $taskId Task id
     * @param int $paginate
     * @return LengthAwarePaginator
     */
    public function getLogsForTask(int $taskId, int $paginate = 50) : LengthAwarePaginator
    {
        return $this->taskLogsModel
            ->where('task_id', $taskId)
            ->orderBy('created_at', 'desc')
            ->with(['task'])
            ->paginate($paginate);
    }

    /**
     * Get all logs
     *
     * @param int $paginate
     * @return LengthAwarePaginator
     */
    public function getLogs(int $paginate = 50) : LengthAwarePaginator
    {
        return $this->taskLogsModel->orderBy('created_at', 'desc')->with(['task'])->paginate($paginate);
    }

    /**
     * Delete logs older than given timestamp
     *
     * @param string $timestamp
     * @return mixed
     */
    public function deleteLogsOlderThan(string $timestamp)
    {
        return $this->taskLogsModel->where('created_at', '<', $timestamp)->delete();
    }
}
