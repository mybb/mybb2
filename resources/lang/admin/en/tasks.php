<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

return [
    'scheduled_tasks' => 'Scheduled Tasks',
    'view_logs'       => 'View Task Logs',
    'add_task'        => 'Add New Task',
    'task_logs'       => 'Task Logs',

    'task'              => 'Task',
    'edit'              => 'Edit task',
    'delete'            => 'Delete task',
    'run'               => 'Run task',
    'disable'           => 'Disable task',
    'enable'            => 'Enable task',
    'create_task'       => 'Create task',
    'show_logs'         => 'Show logs',
    'last_run'          => 'Last run',
    'next_run'          => 'Next run',
    'content'           => 'Content',
    'created_at'        => 'Date',
    'success_disabled'  => 'The selected task has been disabled successfully.',
    'success_enabled'   => 'The selected task has been enabled successfully.',
    'success_deleted'   => 'The selected task has been deleted successfully.',
    'success_update'    => 'The selected task has been updated successfully.',
    'success_save'      => 'The task has been created successfully.',
    'error_ran'         => 'The error occurred while executing task. Check task log.',
    'missing_task_file' => 'Missing task file: :filename',
    'execution_error'   => 'The error occurred while executing task: :message',
    'execution_success' => 'The :task successfully ran.',

    // Form
    'title'             => 'Title',
    'desc'              => 'Short Description',
    'namespace'         => 'Task file',
    'namespace_desc'    => 'Enter a namespace of the task file you wish this task to run.',
    'time'              => [
        'minutes'       => 'Time: Minutes',
        'minutes_desc'  => 'Enter a comma separated list of minutes (0-59) for which this task should run on. Enter \'*\' if this task should run on every minute.',
        'hours'         => 'Time: Hours',
        'hours_desc'    => 'Enter a comma separated list of hours (0-23) for which this task should run on. Enter \'*\' if this task should run on every hour.',
        'days'          => 'Time: Days of Month',
        'days_desc'     => 'Enter a comma separated list of days (1-31) for which this task should run on. Enter \'*\' if this task should run on every day or you wish to specify a weekday below.',
        'weekdays'      => 'Time: Weekdays',
        'weekdays_desc' => 'Select which weekdays this task should run on. Holding down CTRL selects multiple weekdays. Select \'Every weekday\' if you want this task to run each weekday or you have entered a predefined day above.',
        'months'        => 'Time: Months',
        'months_desc'   => 'Select which months this task should run on. Holding down CTRL selects multiple months. Select \'Every month\' if you want this task to run each month.',
        'every_weekday' => 'Every weekday',
        'every_month'   => 'Every month',
    ],
    'logging'           => 'Enable logging?',
    'enabled'           => 'Task enabled?',

    // Tasks
    'tasks'             => [
        'log_pruning'      => 'Log pruning',
        'log_pruning_desc' => 'Automatically cleans up old MyBB log files (administrator, moderator, task, promotion, and mail logs.)',
    ],
];
