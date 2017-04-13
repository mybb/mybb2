<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Tasking;

use Illuminate\Database\DatabaseManager;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use MyBB\Settings\Store;
use MyBB\Core\Database\Models\Task;
use MyBB\Core\Exceptions\TaskFailedException;
use MyBB\Core\Database\Repositories\TasksRepositoryInterface;

class ScheduleManager
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Store
     */
    private $settings;

    /**
     * @var TasksRepositoryInterface
     */
    protected $tasksRepository;

    /**
     * @var CacheRepository
     */
    private $cache;

    /**
     * @var DatabaseManager
     */
    protected $dbManager;

    /**
     * ScheduleManager constructor.
     * @param Application $app
     * @param TasksRepositoryInterface $tasksRepository
     * @param Store $settings
     * @param CacheRepository $cache
     * @param DatabaseManager $dbManager
     */
    public function __construct(
        Application $app,
        TasksRepositoryInterface $tasksRepository,
        Store $settings,
        CacheRepository $cache,
        DatabaseManager $dbManager
    ) {
        $this->app = $app;
        $this->tasksRepository = $tasksRepository;
        $this->settings = $settings;
        $this->cache = $cache;
        $this->dbManager = $dbManager;
    }

    /**
     * Run tasks from Command Line
     *
     * @param Schedule $schedule
     * @return void
     */
    public function runTasksFromCLI(Schedule $schedule)
    {
        // Check if we can grab tasks from database.
        try {
            $tasks = $this->tasksRepository->getEnabledTasks();
            foreach ($tasks as $task) {
                $schedule->call(function () use ($task) {
                    $this->runTask($task);
                })->cron($task->frequency)
                    ->name($task->namespace)
                    ->withoutOverlapping();
            }
        } catch (\Exception $e) {
            unset($e);
        }
    }

    /**
     * Run tasks using page scheduler
     *
     * @return bool
     */
    public function runTasksFromWeb(): bool
    {
        if ($this->cache->has('tasks.fired')) {
            // Nothing to do at this moment. Tasks were ran recently
            return false;
        }
        // Get one task to run
        $task = $this->tasksRepository->getTaskToRun();
        if ($task) {
            $this->runTask($task);
        }
        $this->updateTasksCache();
        return true;
    }

    /**
     * Run task
     *
     * @param Task $task
     * @return bool
     */
    public function runTask(Task $task): bool
    {
        if (!class_exists($task->namespace)) {
            $message = trans('admin::tasks.missing_task_file', ['filename' => $task->namespace]);
            $this->tasksRepository->createLog($task, trans('admin::tasks.execution_error', ['message' => $message]));
            // This task is broken so disable it
            $this->tasksRepository->update($task, ['enabled' => 0]);
            return false;
        }

        $taskClass = $this->app->make($task->namespace);
        if (!($taskClass instanceof AbstractTask)) {
            $message = trans('admin::tasks.not_instance');
            $this->tasksRepository->createLog($task, trans('admin::tasks.execution_error', ['message' => $message]));
            // This task is broken so disable it
            $this->tasksRepository->update($task, ['enabled' => 0]);
            return false;
        }
        // Everything ok, run our task
        try {
            $this->tasksRepository->setTaskAsExecuted($task);
            $taskClass->fire($task);
        } catch (TaskFailedException $e) {
            $this->tasksRepository
                ->createLog($task, trans('admin::tasks.execution_error', ['message' => $e->getMessage()]));
            return false;
        }

        // Task successfully executed
        $this->tasksRepository
            ->createLog($task, trans('admin::tasks.execution_success', ['task' => trans($task->name)]));
        return true;
    }

    /**
     * Update tasks cache (only when page scheduler is used)
     *
     * @return bool|void
     */
    private function updateTasksCache()
    {
        $tasksToRun = $this->tasksRepository->getNumberOfTasksToRun();
        if ($tasksToRun > 0) {
            return false;
        }
        $this->cache->put('tasks.fired', 1, $this->settings->get('tasks.interval'));
    }
}
