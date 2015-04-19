<?php namespace MyBB\Core\Http\Middleware;

use Closure;
use Illuminate\Routing\Router;
use MyBB\Auth\Contracts\Guard;

class UpdateLastVisit extends AbstractBootstrapMiddleware
{
	/** @var Guard */
	protected $guard;

	/** @var Router */
	private $router;

	/**
	 * @param Guard  $guard
	 * @param Router $router
	 */
	public function __construct(Guard $guard, Router $router)
	{
		$this->guard = $guard;
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
		$options = $this->getOptions($this->router, $request);

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
}
