<?php namespace MyBB\Core\Providers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use MyBB\Core\Database\Models\User;
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
			'MyBB\Core\Database\Repositories\PostRepositoryInterface',
			'MyBB\Core\Database\Repositories\Eloquent\PostRepository'
		);

		$this->app->bind(
			'MyBB\Core\Database\Repositories\TopicRepositoryInterface',
			'MyBB\Core\Database\Repositories\Eloquent\TopicRepository'
		);

		$this->app->bind(
			'MyBB\Core\Database\Repositories\UserRepositoryInterface',
			'MyBB\Core\Database\Repositories\Eloquent\UserRepository'
		);

		$this->app->bind(
			'MyBB\Core\Database\Repositories\SearchRepositoryInterface',
			'MyBB\Core\Database\Repositories\Eloquent\SearchRepository'
		);

		$this->app->bind(
			'MyBB\Core\Database\Repositories\PollRepositoryInterface',
			'MyBB\Core\Database\Repositories\Eloquent\PollRepository'
		);

		$this->app->bind(
			'MyBB\Core\Database\Repositories\PollVoteRepositoryInterface',
			'MyBB\Core\Database\Repositories\Eloquent\PollVoteRepository'
		);

		$this->app->bind(
			'MyBB\Core\Database\Repositories\ProfileFieldGroupRepositoryInterface',
			'MyBB\Core\Database\Repositories\Eloquent\ProfileFieldGroupRepository'
		);

		$this->app->bind(
			'MyBB\Core\Database\Repositories\ProfileFieldRepositoryInterface',
			'MyBB\Core\Database\Repositories\Eloquent\ProfileFieldRepository'
		);

		$this->app->bind(
			'MyBB\Core\Database\Repositories\UserProfileFieldRepositoryInterface',
			'MyBB\Core\Database\Repositories\Eloquent\UserProfileFieldRepository'
		);

		$this->app->bind(
			'MyBB\Core\Database\Repositories\ConversationRepositoryInterface',
			'MyBB\Core\Database\Repositories\Eloquent\ConversationRepository'
		);

		$this->app->bind(
			'MyBB\Core\Database\Repositories\ConversationMessageRepositoryInterface',
			'MyBB\Core\Database\Repositories\Eloquent\ConversationMessageRepository'
		);

		$this->app->bind(
			'MyBB\Parser\Parser\CustomCodes\CustomCodeRepositoryInterface',
			function (Application $app) {
				$repository = $app->make('MyBB\Parser\Parser\CustomCodes\CustomMyCodeRepository');
				$cache = $app->make('Illuminate\Contracts\Cache\Repository');

				return new \MyBB\Parser\Parser\CustomCodes\CachingDecorator($repository, $cache);
			}
		);

		$this->app->bind(
			'MyBB\Core\Renderers\Post\Quote\QuoteInterface',
			'MyBB\Core\Renderers\Post\Quote\MyCode'
		);

		$this->app->bind(
			'MyBB\Parser\Parser\ParserInterface',
			'MyBB\Parser\Parser\MyCode'
		);

		$this->app->bind(
			'MyBB\Core\Likes\Database\Repositories\LikesRepositoryInterface',
			'MyBB\Core\Likes\Database\Repositories\Eloquent\LikesRepository'
		);

		$this->app->singleton('MyBB\Core\Permissions\PermissionChecker');

		$this->app->bind(
			'MyBB\Core\Database\Repositories\ForumRepositoryInterface',
			function (Application $app) {
				$repository = $app->make('MyBB\Core\Database\Repositories\Eloquent\ForumRepository');

				$cache = $app->make('Illuminate\Contracts\Cache\Repository');

				$permissionChecker = $app->make('MyBB\Core\Permissions\PermissionChecker');

				$guard = $app->make('Illuminate\Auth\Guard');

				return new CachingDecorator($repository, $cache, $permissionChecker, $guard);
			}
		);

		$this->initDefaultUser();

		// Temporary fix for Form...
		$this->app->bind('Collective\Html\FormBuilder', function ($app) {
			$form = new \Collective\Html\FormBuilder($app['html'], $app['url'], $app['session.store']->getToken());

			return $form->setSessionStore($app['session.store']);
		});

		$this->app->instance('DaveJamesMiller\Breadcrumbs\Manager', $this->app['breadcrumbs']);
	}

	/**
	 * Initialise the default (guest) user, using the custom Guard implementation.
	 */
	private function initDefaultUser()
	{
		/** @var \MyBB\Auth\Contracts\Guard $guard */
		$guard = $this->app->make('Illuminate\Auth\Guard');
		$defaultUser = new User();
		$defaultUser->name = 'Guest';
		$defaultUser->id = -1;
		$guard->registerDefaultUser($defaultUser);
	}
}
