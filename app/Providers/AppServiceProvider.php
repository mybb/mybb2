<?php namespace MyBB\Core\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use MyBB\Core\Database\Repositories\Decorators\Forum\CachingDecorator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'Illuminate\Contracts\Auth\Registrar',
            'MyBB\Core\Services\Registrar'
        );

        $this->app->bind(
            'MyBB\Core\Database\Repositories\IForumRepository',
            function (Application $app) {
                $repository = $app->make('MyBB\Core\Database\Repositories\Eloquent\ForumRepository');

                $cache = $app->make('Illuminate\Contracts\Cache\Repository');

                return new CachingDecorator($repository, $cache);
            }
        );

        $this->app->bind(
            'MyBB\Core\Database\Repositories\IPostRepository',
            'MyBB\Core\Database\Repositories\Eloquent\PostRepository'
        );

        $this->app->bind(
            'MyBB\Core\Database\Repositories\ITopicRepository',
            'MyBB\Core\Database\Repositories\Eloquent\TopicRepository'
        );

        // Temporary fix for Form...
        $this->app->bind('Illuminate\Html\FormBuilder', function($app)
        {
            $form = new \Illuminate\Html\FormBuilder($app['html'], $app['url'], $app['session.store']->getToken());

            return $form->setSessionStore($app['session.store']);
        });
    }
}
