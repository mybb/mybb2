<?php namespace MyBB\Core\Http\Middleware;

use Closure;
use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Permissions\PermissionChecker;
use MyBB\Settings\Store;

class CheckAccess
{
	/** @var Guard */
	protected $auth;

	/** @var  PermissionChecker */
	private $permissionChecker;

	/** @var Store */
	private $settings;

	/**
	 * @param Guard             $auth
	 * @param PermissionChecker $permissionChecker
	 * @param Store             $settings
	 */
	public function __construct(Guard $auth, PermissionChecker $permissionChecker, Store $settings)
	{
		$this->auth = $auth;
		$this->permissionChecker = $permissionChecker;
		$this->settings = $settings;
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

		app()->setLocale($this->settings->get('user.language', 'en'));

		$langDir = [
			'left' => 'left',
			'right' => 'right'
		];

		if (trans('general.direction') == 'rtl') {
			$langDir['left'] = 'right';
			$langDir['right'] = 'left';
		}

		// TODO: The acp should probably create another view
		return view('errors.no_permission')->with('auth_user', $this->auth->user())->with('langDir', $langDir);
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

		return $this->permissionChecker->hasPermission('user', null, $requiredPermisions);
	}
}
