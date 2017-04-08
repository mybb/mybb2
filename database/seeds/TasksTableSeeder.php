<?php
/**
 * @author  MyBB Group
 * @version 2.0.0
 * @package mybb/core
 * @license http://www.mybb.com/licenses/bsd3 BSD-3
 */

use Illuminate\Database\Seeder;

class TasksTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('tasks')->delete();
        $tasksNamespace = 'MyBB\\Core\\Tasking\\Tasks\\';

        $tasks = [
            [
                'namespace' => $tasksNamespace . 'LogPruningTask',
                'frequency' => '0 0 * * *', // Every day at 00:00
                'name'      => 'admin::tasks.tasks.log_pruning',
                'desc'      => 'admin::tasks.tasks.log_pruning_desc',
                'last_run'  => time(),
                'next_run'  => time(),
                'enabled'   => 1,
                'logging'   => 1,
            ],
        ];

        DB::table('tasks')->insert($tasks);
    }
}
