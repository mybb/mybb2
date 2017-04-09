<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\Paginator;
use MyBB\Core\Database\Models\Task;

interface TasksRepositoryInterface
{
    /**
     * Get all tasks
     *
     * @return Collection
     */
    public function all() : Collection;

    /**
     * Find one task by id
     *
     * @param int $id Task id
     * @return Task
     */
    public function find(int $id) : Task;

    /**
     * Update Task
     *
     * @param Task $task Task Model
     * @param array $options Tables to update
     * @return bool
     */
    public function update(Task $task, array $options = []) : bool;

    /**
     * Create new Task
     *
     * @param array $options
     * @return Task
     */
    public function createTask(array $options = []) : Task;

    /**
     * Delete task with all related logs
     *
     * @param Task $task
     * @return bool|null
     */
    public function deleteWithLogs(Task $task);

    /**
     * Get all enabled tasks
     *
     * @return Collection
     */
    public function getEnabledTasks() : Collection;

    /**
     * Get first task to run
     *
     * @return  Task|null
     */
    public function getTaskToRun();

    /**
     * Get all task that need be run
     *
     * @return Collection
     */
    public function getTasksToRun() : Collection;

    /**
     * Set task execution timestamps
     *
     * @param Task $task Task Model
     * @return bool
     */
    public function setTaskAsExecuted(Task $task) : bool;

    /**
     * Get task number that need to be run
     *
     * @return int Number of tasks
     */
    public function getNumberOfTasksToRun() : int;

    /**
     * Create task log
     *
     * @param Task $task Task Model
     * @param string $content Log content
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createLog(Task $task, string $content);

    /**
     * Get all logs for specified task
     *
     * @param int $taskId Task id
     * @param int $paginate
     * @return Paginator
     */
    public function getLogsForTask(int $taskId, int $paginate = 50) : Paginator;

    /**
     * Get all logs
     *
     * @param int $paginate
     * @return Paginator
     */
    public function getLogs(int $paginate = 50) : Paginator;

    /**
     * Delete logs older than given timestamp
     *
     * @param string $timestamp
     * @return mixed
     */
    public function deleteLogsOlderThan(string $timestamp);
}
