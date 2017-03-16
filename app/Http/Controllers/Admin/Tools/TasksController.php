<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Controllers\Admin\Tools;

use Illuminate\Http\Request;
use Illuminate\Contracts\Foundation\Application;
use Cron\CronExpression;
use DaveJamesMiller\Breadcrumbs\Manager as Breadcrumbs;
use MyBB\Core\Tasking\ScheduleManager;
use MyBB\Core\Exceptions\TaskNotFoundException;
use MyBB\Core\Http\Controllers\Admin\AdminController;
use MyBB\Core\Http\Requests\Tools\CreateOrUpdateTaskRequest;
use MyBB\Core\Database\Repositories\TasksRepositoryInterface;

class TasksController extends AdminController
{

    /**
     * @var TasksRepositoryInterface
     */
    protected $tasksRepository;

    /**
     * @var Application
     */
    protected $app;

    /**
     * @var Breadcrumbs
     */
    protected $breadcrumbs;

    /**
     * TasksController constructor.
     * @param TasksRepositoryInterface $tasksRepository
     * @param Application $app
     * @param Breadcrumbs $breadcrumbs
     */
    public function __construct(TasksRepositoryInterface $tasksRepository, Application $app, Breadcrumbs $breadcrumbs)
    {
        $this->tasksRepository = $tasksRepository;
        $this->app = $app;
        $this->breadcrumbs = $breadcrumbs;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show()
    {
        $this->breadcrumbs->setCurrentRoute('admin.tools.tasks');
        $tasks = $this->tasksRepository->all();
        foreach ($tasks as $key => $task) {
            if (!class_exists($task->namespace)) {
                // There is no such task file. Show information about it
                $task->displayName = trans('admin::tasks.missing_task_file', ['filename' => $task->namespace]);
                continue;
            }
        }

        return view('admin.tools.tasks.show', compact('tasks'))->withActive('tasks');
    }

    /**
     * @param int $id
     * @param ScheduleManager $schedule
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function run(int $id, ScheduleManager $schedule)
    {
        $task = $this->tasksRepository->find($id);
        if (!$task || !$task->namespace) {
            throw new TaskNotFoundException;
        }

        $result = $schedule->runTask($task);
        if ($result) {
            return redirect()
                ->route('admin.tools.tasks')
                ->withSuccess(trans('admin::tasks.execution_success', ['task' => $task->name]));
        } else {
            return redirect()->route('admin.tools.tasks')->withError(trans('admin::tasks.error_ran'));
        }
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(int $id)
    {
        $this->breadcrumbs->setCurrentRoute('admin.tools.tasks.edit');
        $task = $this->tasksRepository->find($id);
        if (!$task) {
            throw new TaskNotFoundException;
        }

        $time = explode(' ', $task->frequency);
        // Explode month [3] and day of week [4] for select inputs
        $time[3] = explode(',', $time[3]);
        $time[4] = explode(',', $time[4]);


        return view('admin.tools.tasks.edit', compact('task', 'time'))->withActive('tasks');
    }

    /**
     * @param int $id
     * @param CreateOrUpdateTaskRequest $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(int $id, CreateOrUpdateTaskRequest $request)
    {
        $inputs = $request->except('_csrf');
        $task = $this->tasksRepository->find($inputs['id']);
        if (!$task) {
            throw new TaskNotFoundException;
        }

        // Format time inputs to cron expression
        $cron_format = $this->timeInputsToCronExpression($request->get('time'));
        // Calculate next run date
        $dt = new \DateTime();
        $cron = CronExpression::factory($cron_format);
        $next_run = $cron->getNextRunDate($dt->setTimestamp($task->last_run))->getTimestamp();

        $this->tasksRepository->update($task, [
            'name'      => $inputs['name'],
            'desc'      => $inputs['desc'],
            'namespace' => $inputs['namespace'],
            'frequency' => $cron_format,
            'next_run'  => $next_run,
            'logging'   => isset($inputs['logging']),
            'enabled'   => isset($inputs['enabled']),
        ]);

        return redirect()->route('admin.tools.tasks')->withSuccess(trans('admin::tasks.success_update'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $this->breadcrumbs->setCurrentRoute('admin.tools.tasks.create');
        return view('admin.tools.tasks.create')->withActive('tasks');
    }

    /**
     * @param CreateOrUpdateTaskRequest $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function save(CreateOrUpdateTaskRequest $request)
    {
        $inputs = $request->except('_csrf');
        // Format time inputs to cron expression
        $cron_format = $this->timeInputsToCronExpression($request->get('time'));

        $this->tasksRepository->createTask([
            'name'      => $inputs['name'],
            'desc'      => $inputs['desc'],
            'namespace' => $inputs['namespace'],
            'frequency' => $cron_format,
            'last_run'  => time(),
            'next_run'  => time(),
            'logging'   => isset($inputs['logging']),
            'enabled'   => isset($inputs['enabled']),
        ]);

        return redirect()->route('admin.tools.tasks')->withSuccess(trans('admin::tasks.success_save'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function disableEnable(Request $request)
    {
        $task = $this->tasksRepository->find($request->get('id'));
        if (!$task) {
            throw new TaskNotFoundException;
        }

        if ($task->enabled == 0) {
            $action = ['enabled' => 1]; // enable task
            $lang = 'admin::tasks.success_enabled';
        } else {
            $action = ['enabled' => 0]; // disable task
            $lang = 'admin::tasks.success_disabled';
        }

        $this->tasksRepository->update($task, $action);
        return redirect()->route('admin.tools.tasks')->withSuccess(trans($lang));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        $task = $this->tasksRepository->find($request->get('id'));
        if (!$task) {
            throw new TaskNotFoundException;
        }

        $this->tasksRepository->deleteWithLogs($task);
        return redirect()->route('admin.tools.tasks')->withSuccess(trans('admin::tasks.success_deleted'));
    }

    /**
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function logs($id = null)
    {
        $this->breadcrumbs->setCurrentRoute('admin.tools.tasks.logs');
        if ($id) {
            $logs = $this->tasksRepository->getLogsForTask($id);
        } else {
            $logs = $this->tasksRepository->getLogs();
        }

        return view('admin.tools.tasks.logs', compact('logs'))->withActive('tasks');
    }

    /**
     * Format time inputs to cron expression
     *
     * @param array $inputs
     * @return string
     */
    private function timeInputsToCronExpression(array $inputs = []): string
    {
        $cron_format = '';
        foreach ($inputs as $key => $value) {
            if ($key === 'month' || $key === 'weekday') {
                if (in_array('*', $value)) {
                    $cron_format .= ' *';
                } else {
                    $cron_format .= ' ' . implode(',', $value);
                }
            } elseif ($key === 'minutes') {
                $cron_format .= $value;
            } else {
                $cron_format .= ' ' . $value;
            }
        }

        return $cron_format;
    }
}
