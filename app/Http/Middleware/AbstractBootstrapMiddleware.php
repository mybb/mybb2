<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;

abstract class AbstractBootstrapMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  \Closure $next
     *
     * @return mixed
     */
    abstract public function handle($request, Closure $next);

    /**
     * @param Router $router
     * @param Request $request
     *
     * @return array
     */
    protected function getOptions(Router $router, Request $request)
    {
        $collection = $router->getRoutes();
        $route = $collection->match($request->create($request->path(), $request->method()));

        return $route->getAction();
    }
}
