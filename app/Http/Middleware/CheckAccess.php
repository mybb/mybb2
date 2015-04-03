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

		// TODO: The except setting doesn't work atm. First I need to look at guests and how we handle this

		// First check whether guest are not allowed to access this page
		if(!$this->auth->check())
		{
			// Guests are set to except
			if(isset($action['except']) && $action['except'] == 'guest')
			{
				return false;
			}
		}

		// Check if route is protected
		if(isset($action['except']))
		{
			// Check if our role is allowed
			$notAllowed = explode('|', $action['except']);


			if(in_array($this->auth->user()->role->role_slug, $notAllowed))
			{
				return false;
			}
		}

		// Did we get this far without a false. This is the final check.

		return $this->auth->user()->canAccess($requiredPermisions);
	}
}
