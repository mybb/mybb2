<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use MyBB\Core\Tasking\AbstractTask;
use MyBB\Core\Tasking\ScheduleManager;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Events\Dispatcher;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'MyBB\Core\Console\Commands\RecountCommand',
        'MyBB\Core\Console\Commands\TaskMakeCommand',
    ];

    protected $app;

    /**
     * Kernel constructor.
     * @param Application $app
     * @param Dispatcher $events
     */
    public function __construct(Application $app, Dispatcher $events)
    {
        parent::__construct($app, $events);

        $this->app = $app;
    }

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $manager = $this->app->make(ScheduleManager::class);
        $manager->runTasksFromCLI($schedule);
    }
}
