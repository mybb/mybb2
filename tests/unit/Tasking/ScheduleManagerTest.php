<?php

namespace MyBB\Core\Tasking;

use Mockery;

class ScheduleManagerTest extends \PHPUnit_Framework_TestCase
{
    public function testCanBeConstructed()
    {
        $app = Mockery::mock(\Illuminate\Contracts\Foundation\Application::class);
        $tasksRepository = Mockery::mock(\MyBB\Core\Database\Repositories\TasksRepositoryInterface::class);
        $settings = Mockery::mock(\MyBB\Settings\Store::class);
        $cache = Mockery::mock(\Illuminate\Contracts\Cache\Repository::class);

        $schedule = new ScheduleManager($app, $tasksRepository, $settings, $cache);

        static::assertInstanceOf(ScheduleManager::class, $schedule);
    }
}
