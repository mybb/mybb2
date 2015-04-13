<?php

namespace MyBB\Core\Repository;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('MyBB\Core\Repository\RepositoryRegistry', function ($app) {
            return new RepositoryRegistry([
                'post' => 'MyBB\Core\Database\Repositories\PostRepositoryInterface',
                'topic' => 'MyBB\Core\Database\Repositories\TopicRepositoryInterface',
            ]);
        });
    }
}
