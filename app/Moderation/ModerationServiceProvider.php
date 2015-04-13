<?php

namespace MyBB\Core\Moderation;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use MyBB\Core\Moderation\Moderations\Approve;

class ModerationServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('MyBB\Core\Moderation\ModerationRegistry', function (Application $app) {
            return new ModerationRegistry([
                new Approve(),
                $app->make('MyBB\Core\Moderation\Moderations\MovePost'),
                $app->make('MyBB\Core\Moderation\Moderations\MergePosts'),
            ]);
        });
    }
}
