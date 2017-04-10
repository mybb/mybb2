<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    ];
    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \MyBB\Core\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \MyBB\Core\Http\Middleware\VerifyCsrfToken::class,
            //\MyBB\Settings\Middleware\SaveSettingsOnTerminate::class,
            \MyBB\Core\Http\Middleware\SetupLanguage::class,
            \MyBB\Core\Http\Middleware\ShareViewVariables::class,
            \MyBB\Core\Http\Middleware\UpdateLastVisit::class,
            \MyBB\Core\Http\Middleware\AckWithWarn::class,
        ],
        'api' => [
            'throttle:60,1',
        ],
    ];
    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth'         => \MyBB\Core\Http\Middleware\Authenticate::class,
        'auth.basic'   => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest'        => \MyBB\Core\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle'     => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'checkaccess'  => \MyBB\Core\Http\Middleware\CheckAccess::class,
        'checksetting' => \MyBB\Core\Http\Middleware\CheckSetting::class,
        'runtasks'     => \MyBB\Core\Http\Middleware\RunTasks::class,
    ];
}
