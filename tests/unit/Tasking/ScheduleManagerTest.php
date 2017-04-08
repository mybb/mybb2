<?php

namespace MyBB\Core\Tasking;

use Mockery;

class ScheduleManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testCanBeConstructed()
    {
        $app = Mockery::mock('Illuminate\Contracts\Foundation\Application');
        $tasksRepository = Mockery::mock('MyBB\Core\Database\Repositories\TasksRepositoryInterface');
        $settings = Mockery::mock('MyBB\Settings\Store');
        $cache = Mockery::mock('Illuminate\Contracts\Cache\Repository');

        $schedule = new ScheduleManager($app, $tasksRepository, $settings, $cache);

        static::assertInstanceOf('MyBB\Core\Tasking\ScheduleManager', $schedule);
    }
}
