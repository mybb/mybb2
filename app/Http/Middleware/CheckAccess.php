<?php namespace MyBB\Core\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class CheckAccess
{

	protected $auth;

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
		if($this->checkPermissions($request))
		{
			return $next($request);
		}

		// TODO: The acp should probably create another view
		return view('errors.no_permission');
	}

	/**
	 *  Check Permissions
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return Boolean True if permission check passes, false otherwise
	 */
	protected function checkPermissions($request)
	{
		$action = $request->route()->getAction();
		// Check for additional permissions required
		$requiredPermisions = isset($action['permissions']) ? explode('|', $action['permissions']) : false;

		return $this->auth->user()->hasPermission($requiredPermisions);
	}
}
