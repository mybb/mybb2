<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Middleware;

use Closure;
use MyBB\Settings\Store;
use MyBB\Core\Tasking\ScheduleManager;

class RunTasks extends AbstractBootstrapMiddleware
{
    /**
     * @var ScheduleManager
     */
    protected $schedule;

    /**
     * @var Store
     */
    private $settings;

    /**
     * @param Store $settings
     * @param ScheduleManager $schedule
     */
    public function __construct(Store $settings, ScheduleManager $schedule)
    {
        $this->settings = $settings;
        $this->schedule = $schedule;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    /**
     * Perform any final actions for the request lifecycle.
     *
     * @param $request
     * @param $response
     * @return void
     */
    public function terminate($request, $response)
    {
        if ($this->settings->get('tasks.on_page')) {
            $this->schedule->runTasksFromWeb();
        }
    }
}
