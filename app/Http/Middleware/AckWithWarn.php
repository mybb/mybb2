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
use Illuminate\Routing\Route;

class AckWithWarn extends AbstractBootstrapMiddleware
{
    /**
     * @var Guard
     */
    private $guard;

    /**
     * @var Route
     */
    private $route;

    /**
     * @param Guard $guard
     * @param Route $route
     */
    public function __construct(Guard $guard, Route $route)
    {
        $this->guard = $guard;
        $this->route = $route;
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
        if ($this->guard->check()) {
            if ($this->guard->user()->warned && $this->route->getName() !== 'warnings.ack') {
                return redirect()->route('warnings.ack');
            }
        }

        return $next($request);
    }
}
