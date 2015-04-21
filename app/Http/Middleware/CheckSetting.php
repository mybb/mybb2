<?php namespace MyBB\Core\Http\Middleware;

use Closure;
use MyBB\Auth\Contracts\Guard;
use MyBB\Core\Permissions\PermissionChecker;
use MyBB\Settings\Store;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckSetting
{
	/**
	 * @var Store
	 */
	protected $settings;

	/**
	 * @param Store $settings
	 */
	public function __construct(Store $settings)
	{
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
		if ($this->checkSetting($request)) {
			return $next($request);
		}

		throw new NotFoundHttpException;
	}

	/**
	 *  Check Setting
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return Boolean True if setting is true
	 */
	protected function checkSetting($request)
	{
		$action = $request->route()->getAction();

		$setting = $action['setting'];

		return $this->settings->get($setting, null, false);
	}
}
