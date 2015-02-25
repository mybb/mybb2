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
		if ($this->checkPermissions($request)) {
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

		// Weed out the Guests first
		if (!$this->auth->user()) {
			// Guests are set to except
			if (isset($action['except']) && $action['except'] == 'guest') {
				return false;
			}

			// Don't require permissions? Good to go
			if (!$requiredPermisions) {
				return true;
			}

			// TODO: How can we easily check permissions for guests?
			// As all of the things below would throw an error for guests atm we simply let them die
			return false;
		}

		// Check if route is protected
		if (isset($action['except'])) {
			// Check if our role is allowed
			$notAllowed = explode('|', $action['except']);


			if (!$this->auth->user()->role || in_array($this->auth->user()->role->role_slug, $notAllowed)) {
				return false;
			}
		}

		// Did we get this far without a false. This is the final check.

		return $this->auth->user()->canAccess($requiredPermisions);
	}
}
