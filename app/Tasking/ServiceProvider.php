<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Tasking;

use MyBB\Core\Tasking\Tasks\ExampleTask;
use MyBB\Core\Tasking\Tasks\LogPruningTask;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Artisan commands
     *
     * @var array
     */
    protected $tasks = [
        ExampleTask::class,
        LogPruningTask::class,
    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->commands($this->tasks);
    }
}
