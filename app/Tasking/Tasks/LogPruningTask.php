<?php

/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Tasking\Tasks;

use MyBB\Core\Database\Models\Task;
use MyBB\Core\Tasking\AbstractTask;
use MyBB\Core\Database\Repositories\TasksRepositoryInterface;
use MyBB\Core\Database\Repositories\ModerationLogRepositoryInterface;
use MyBB\Settings\Store;

class LogPruningTask extends AbstractTask
{
    /**
     * @var string
     */
    protected $name = 'task:log_pruning';

    /**
     * @var string
     */
    protected $description = 'Automatically cleans up old MyBB log files 
    (administrator, moderator, task, promotion, and mail logs.)';

    /**
     * @var Store
     */
    protected $settings;

    /**
     * @var TasksRepositoryInterface
     */
    protected $tasksRepository;

    /**
     * @var ModerationLogRepositoryInterface
     */
    protected $moderationLogRepository;

    /**
     * ExampleTask constructor.
     *
     * @param Store $settings
     * @param TasksRepositoryInterface $tasksRepository
     * @param ModerationLogRepositoryInterface $moderationLogRepository
     */
    public function __construct(
        Store $settings,
        TasksRepositoryInterface $tasksRepository,
        ModerationLogRepositoryInterface $moderationLogRepository
    ) {
        parent::__construct();
        $this->settings = $settings;
        $this->tasksRepository = $tasksRepository;
        $this->moderationLogRepository = $moderationLogRepository;
    }

    /**
     * @param Task $task Task Model
     * @return void
     */
    public function fire(Task $task)
    {
        $this->clearTaskLogs();
        $this->clearModerationLogs();
    }

    /**
     * @return mixed
     */
    protected function clearTaskLogs()
    {
        $date = new \DateTime;
        $date->modify("-{$this->settings->get('tasks.max_log_life')} days");
        $timestamp = $date->format('Y-m-d H:i:s');

        return $this->tasksRepository->deleteLogsOlderThan($timestamp);
    }

    /**
     * @return mixed
     */
    protected function clearModerationLogs()
    {
        $date = new \DateTime;
        $date->modify("-{$this->settings->get('moderation.max_log_life')} days");
        $timestamp = $date->format('Y-m-d H:i:s');

        return $this->moderationLogRepository->deleteOlderThan($timestamp);
    }
}
