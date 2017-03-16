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
use MyBB\Core\Exceptions\TaskFailedException;
use MyBB\Core\Database\Repositories\TasksRepositoryInterface;

class ExampleTask extends AbstractTask
{
    /**
     * @var string
     */
    protected $name = 'task:example';

    /**
     * @var string
     */
    protected $description = 'Just for test and present tasks functionality';

    /**
     * @var TasksRepositoryInterface
     */
    protected $tasksRepository;

    /**
     * ExampleTask constructor.
     * @param TasksRepositoryInterface $tasksRepository
     */
    public function __construct(TasksRepositoryInterface $tasksRepository)
    {
        parent::__construct();

        $this->tasksRepository = $tasksRepository;
    }

    /**
     * @param Task $task Task Model
     * @return void
     */
    public function fire(Task $task)
    {
        // Custom log
        $this->tasksRepository->createLog($task, 'Custom task log');

        throw new TaskFailedException('There was an error');
    }
}
