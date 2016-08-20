<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Providers;

use Collective\Html\FormBuilder;
use DaveJamesMiller\Breadcrumbs\Manager;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use MyBB\Core\Database\Repositories\ConversationMessageRepositoryInterface;
use MyBB\Core\Database\Repositories\ConversationRepositoryInterface;
use MyBB\Core\Database\Repositories\Decorators\Forum\CachingDecorator;
use MyBB\Core\Database\Repositories\Eloquent\ConversationMessageRepository;
use MyBB\Core\Database\Repositories\Eloquent\ConversationRepository;
use MyBB\Core\Database\Repositories\Eloquent\ForumRepository;
use MyBB\Core\Database\Repositories\Eloquent\PollRepository;
use MyBB\Core\Database\Repositories\Eloquent\PollVoteRepository;
use MyBB\Core\Database\Repositories\Eloquent\PostRepository;
use MyBB\Core\Database\Repositories\Eloquent\ProfileFieldGroupRepository;
use MyBB\Core\Database\Repositories\Eloquent\ProfileFieldOptionRepository;
use MyBB\Core\Database\Repositories\Eloquent\ProfileFieldRepository;
use MyBB\Core\Database\Repositories\Eloquent\SearchRepository;
use MyBB\Core\Database\Repositories\Eloquent\TopicRepository;
use MyBB\Core\Database\Repositories\Eloquent\UserProfileFieldRepository;
use MyBB\Core\Database\Repositories\Eloquent\UserRepository;
use MyBB\Core\Database\Repositories\ForumRepositoryInterface;
use MyBB\Core\Database\Repositories\PollRepositoryInterface;
use MyBB\Core\Database\Repositories\PollVoteRepositoryInterface;
use MyBB\Core\Database\Repositories\PostRepositoryInterface;
use MyBB\Core\Database\Repositories\ProfileFieldGroupRepositoryInterface;
use MyBB\Core\Database\Repositories\ProfileFieldOptionRepositoryInterface;
use MyBB\Core\Database\Repositories\ProfileFieldRepositoryInterface;
use MyBB\Core\Database\Repositories\SearchRepositoryInterface;
use MyBB\Core\Database\Repositories\TopicRepositoryInterface;
use MyBB\Core\Database\Repositories\UserProfileFieldRepositoryInterface;
use MyBB\Core\Database\Repositories\UserRepositoryInterface;
use MyBB\Core\Likes\Database\Repositories\Eloquent\LikesRepository;
use MyBB\Core\Likes\Database\Repositories\LikesRepositoryInterface;
use MyBB\Core\Permissions\PermissionChecker;
use MyBB\Core\Renderers\Post\Quote\MyCode;
use MyBB\Core\Renderers\Post\Quote\QuoteInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang/admin', 'admin');
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
            PostRepositoryInterface::class,
            PostRepository::class
        );

        $this->app->bind(
            TopicRepositoryInterface::class,
            TopicRepository::class
        );

        $this->app->bind(
            UserRepositoryInterface::class,
            UserRepository::class
        );

        $this->app->bind(
            SearchRepositoryInterface::class,
            SearchRepository::class
        );

        $this->app->bind(
            PollRepositoryInterface::class,
            PollRepository::class
        );

        $this->app->bind(
            PollVoteRepositoryInterface::class,
            PollVoteRepository::class
        );

        $this->app->bind(
            ProfileFieldGroupRepositoryInterface::class,
            ProfileFieldGroupRepository::class
        );

        $this->app->bind(
            ProfileFieldRepositoryInterface::class,
            ProfileFieldRepository::class
        );

        $this->app->bind(
            ProfileFieldOptionRepositoryInterface::class,
            ProfileFieldOptionRepository::class
        );

        $this->app->bind(
            UserProfileFieldRepositoryInterface::class,
            UserProfileFieldRepository::class
        );

        $this->app->bind(
            ConversationRepositoryInterface::class,
            ConversationRepository::class
        );

        $this->app->bind(
            ConversationMessageRepositoryInterface::class,
            ConversationMessageRepository::class
        );

        $this->app->bind(
            QuoteInterface::class,
            MyCode::class
        );

        $this->app->bind(
            LikesRepositoryInterface::class,
            LikesRepository::class
        );

        $this->app->singleton(PermissionChecker::class);

        $this->app->bind(
            ForumRepositoryInterface::class,
            function (Application $app) {
                $repository = $app->make(ForumRepository::class);

                $cache = $app->make(Repository::class);

                $permissionChecker = $app->make(PermissionChecker::class);

                $guard = $app->make(Guard::class);

                return new CachingDecorator($repository, $cache, $permissionChecker, $guard);
            }
        );

        // TODO: Default user (Guest) = $this->initDefaultUser();

        $this->app->instance(Manager::class, $this->app['breadcrumbs']);

        // Fix for the form builder
        $this->app->bind(FormBuilder::class, function ($app) {
            $form = new FormBuilder($app['html'], $app['url'], $app['view'], $app['session.store']->getToken());

            return $form->setSessionStore($app['session.store']);
        });
    }
}
