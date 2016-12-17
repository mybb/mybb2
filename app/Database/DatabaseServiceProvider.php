<?php

/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Database;

use MyBB\Core\Kernel\AbstractServiceProvider;
use MyBB\Core\Kernel\Application;
use Illuminate\Database\ConnectionResolver;
use Illuminate\Database\Connectors\ConnectionFactory;
use PDO;

class DatabaseServiceProvider extends AbstractServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->app->singleton('mybb.db', function () {
            $factory = new ConnectionFactory($this->app);

            $connection = $factory->make($this->app->config('database'));
            $connection->setEventDispatcher($this->app->make('Illuminate\Contracts\Events\Dispatcher'));
            $connection->setFetchMode(PDO::FETCH_CLASS);

            return $connection;
        });

        $this->app->alias('mybb.db', 'Illuminate\Database\ConnectionInterface');

        $this->app->singleton('Illuminate\Database\ConnectionResolverInterface', function () {
            $resolver = new ConnectionResolver([
                'mybb' => $this->app->make('mybb.db'),
            ]);
            $resolver->setDefaultConnection('mybb');

            return $resolver;
        });

        $this->app->alias('Illuminate\Database\ConnectionResolverInterface', 'db');

        $this->app->singleton('MyBB\Core\Database\MigrationRepositoryInterface', function ($app) {
            return new DatabaseMigrationRepository($app['db'], 'migrations');
        });

        $this->app->bind(MigrationCreator::class, function (Application $app) {
            return new MigrationCreator($app->make('Illuminate\Filesystem\Filesystem'), $app->basePath());
        });
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        if ($this->app->isInstalled()) {
            AbstractModel::setConnectionResolver($this->app->make('Illuminate\Database\ConnectionResolverInterface'));
            AbstractModel::setEventDispatcher($this->app->make('events'));
        }
    }
}
