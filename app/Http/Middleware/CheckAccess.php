<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use MyBB\Core\Permissions\PermissionChecker;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class CheckAccess
{
    /**
     * @var Guard
     */
    protected $auth;

    /**
     * @var PermissionChecker
     */
    private $permissionChecker;

    /**
     * @param Guard $auth
     * @param PermissionChecker $permissionChecker
     */
    public function __construct(Guard $auth, PermissionChecker $permissionChecker)
    {
        $this->auth = $auth;
        $this->permissionChecker = $permissionChecker;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->checkPermissions($request)) {
            return $next($request);
        }

        throw new AccessDeniedHttpException;
    }

    /**
     *  Check Permissions
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Boolean True if permission check passes, false otherwise
     */
    protected function checkPermissions($request) : bool
    {
        $action = $request->route()->getAction();
        // Check for additional permissions required
        $requiredPermisions = isset($action['permissions']) ? explode('|', $action['permissions']) : false;

        return $this->permissionChecker->hasPermission('user', null, $requiredPermisions);
    }
}
