<?php namespace MyBB\Core\Http\Middleware;

use Closure;
use Illuminate\View\Factory;
use MyBB\Auth\Contracts\Guard;

class ShareViewVariables extends AbstractBootstrapMiddleware
{
	/** @var Guard */
	protected $guard;

	/** @var Factory */
	private $viewFactory;

	/**
	 * @param Guard   $guard
	 * @param Factory $viewFactory
	 */
	public function __construct(Guard $guard, Factory $viewFactory)
	{
		$this->guard = $guard;
		$this->viewFactory = $viewFactory;
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
		// Share the authenticated user to make sure we have a decorated version of it
		$this->viewFactory->share('auth_user', $this->guard->user());

		return $next($request);
	}
}
