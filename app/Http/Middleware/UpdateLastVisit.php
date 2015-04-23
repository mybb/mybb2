<?php namespace MyBB\Core\Http\Middleware;

use Closure;
use Illuminate\Config\Repository;
use Illuminate\Routing\Router;
use MyBB\Auth\Contracts\Guard;

class UpdateLastVisit extends AbstractBootstrapMiddleware
{
	/**
	 * @var Guard
	 */
	protected $guard;

	/**
	 * @var Router
	 */
	private $router;

	/**
	 * @var Repository
	 */
	private $config;

	/**
	 * @param Guard      $guard
	 * @param Router     $router
	 * @param Repository $config
	 */
	public function __construct(Guard $guard, Router $router, Repository $config)
	{
		$this->guard = $guard;
		$this->router = $router;
		$this->config = $config;
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
		$options = $this->getOptions($this->router, $request);

		if ($this->inDebugbar($request)) {
			return $next($request);
		}

		if (!isset($options['noOnline']) || $options['noOnline'] !== true) {
			if ($this->guard->check()) {
				$this->guard->user()->update([
					'last_visit' => new \DateTime(),
					'last_page' => $request->path()
				]);
			}
		}

		return $next($request);
	}

	/**
	 * @param \Illuminate\Http\Request $request
	 *
	 * @return bool
	 */
	private function inDebugbar($request)
	{
		$enabled = $this->config->get('debugbar.enabled');

		if (!$enabled) {
			return false;
		}

		return starts_with($request->path(), $this->config->get('debugbar.route_prefix'));
	}
}
