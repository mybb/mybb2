<?php namespace MyBB\Core\Http\Middleware;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\View\Factory;
use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Permissions\PermissionChecker;
use MyBB\Settings\Store;

class BootstrapMyBB
{
	/** @var Guard */
	protected $guard;

	/** @var Factory */
	private $viewFactory;

	/** @var Store */
	private $settings;

	/** @var Application */
	private $app;

	/** @var Router */
	private $router;

	/**
	 * @param Guard       $guard
	 * @param Factory     $viewFactory
	 * @param Store       $settings
	 * @param Application $app
	 * @param Router      $router
	 */
	public function __construct(Guard $guard, Factory $viewFactory, Store $settings, Application $app, Router $router)
	{
		$this->guard = $guard;
		$this->viewFactory = $viewFactory;
		$this->settings = $settings;
		$this->app = $app;
		$this->router = $router;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure                 $next
	 *
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		// The route settings aren't loaded at this point so we need to get it manually
		$options = $this->getOptions($request);

		// Share the authenticated user to make sure we have a decorated version of it
		$this->viewFactory->share('auth_user', $this->guard->user());

		// Set language related settings
		$this->setLanguage();

		if (!isset($options['noOnline']) || $options['noOnline'] !== true) {
			$this->updateLastVisit($request);
		}

		return $next($request);
	}

	/**
	 * @param $request
	 *
	 * @return array
	 */
	private function getOptions($request)
	{
		$collection = $this->router->getRoutes();
		$route = $collection->match($request->create($request->path()));

		return $route->getAction();
	}

	private function setLanguage()
	{
		$this->app->setLocale($this->settings->get('user.language', 'en'));

		$langDir = [
			'left' => 'left',
			'right' => 'right'
		];
		if (trans('general.direction') == 'rtl') {
			$langDir['left'] = 'right';
			$langDir['right'] = 'left';
		}

		$this->viewFactory->share('langDir', $langDir);
	}

	/**
	 * @param $request
	 */

	private function updateLastVisit($request)
	{
		if ($this->guard->check()) {
			$this->guard->user()->update([
				'last_visit' => new \DateTime(),
				'last_page' => $request->path()
			]);
		}

	}
}
