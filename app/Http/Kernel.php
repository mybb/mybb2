<?php namespace MyBB\Core\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{

	/**
	 * The application's global HTTP middleware stack.
	 *
	 * @var array
	 */
	protected $middleware = [
		'Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
		'Illuminate\Cookie\Middleware\EncryptCookies',
		'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse',
		'Illuminate\Session\Middleware\StartSession',
		'Illuminate\View\Middleware\ShareErrorsFromSession',
		'Illuminate\Foundation\Http\Middleware\VerifyCsrfToken',
		'MyBB\Settings\Middleware\SaveSettingsOnTerminate',
		'MyBB\Core\Http\Middleware\SetupLanguage',
		'MyBB\Core\Http\Middleware\ShareViewVariables',
		'MyBB\Core\Http\Middleware\UpdateLastVisit',
	];

	/**
	 * The application's route middleware.
	 *
	 * @var array
	 */
	protected $routeMiddleware = [
		'auth' => 'MyBB\Core\Http\Middleware\Authenticate',
		'auth.basic' => 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth',
		'guest' => 'MyBB\Core\Http\Middleware\RedirectIfAuthenticated',
		'checkaccess' => 'MyBB\Core\Http\Middleware\CheckAccess',
		'checksetting' => 'MyBB\Core\Http\Middleware\CheckSetting',
	];
}
