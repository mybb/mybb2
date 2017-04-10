<?php

/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */
namespace MyBB\Core\Tasking;

use Illuminate\Console\Command;
use MyBB\Core\Database\Models\Task;

abstract class AbstractTask extends Command
{
    /**
     * Command name
     *
     * @var string
     */
    protected $name;

    /**
     * Task description
     *
     * @var string
     */
    protected $description;

    /**
     * Main task function
     *
     * @param Task $task Task Model
     * @return mixed
     */
    abstract public function fire(Task $task);
}
