<?php namespace MyBB\Core\Http\Middleware;

use Closure;
use MyBB\Auth\Contracts\Guard;
use Illuminate\Http\RedirectResponse;

class RedirectIfAuthenticated
{

	/**
	 * The Guard implementation.
	 *
	 * @var Guard
	 */
	protected $auth;

	/**
	 * Create a new filter instance.
	 *
	 * @param  Guard $auth
	 */
	public function __construct(Guard $auth)
	{
		$this->auth = $auth;
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
		if ($this->auth->check()) {
			return new RedirectResponse(url('/'));
		}

		return $next($request);
	}
}
