<?php

/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http;

use MyBB\Core\Kernel\Application;

class AbstractUrlGenerator
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var RouteCollection
     */
    protected $routes;

    /**
     * @var string|null
     */
    protected $path;

    /**
     * @param Application $app
     * @param RouteCollection $routes
     */
    public function __construct(Application $app, RouteCollection $routes)
    {
        $this->app = $app;
        $this->routes = $routes;
    }

    /**
     * Generate a URL to a named route.
     *
     * @param string $name
     * @param array $parameters
     * @return string
     */
    public function toRoute($name, $parameters = [])
    {
        $path = $this->routes->getPath($name, $parameters);
        $path = ltrim($path, '/');

        return $this->toBase().'/'.$path;
    }

    /**
     * Generate a URL to a path.
     *
     * @param string $path
     * @return string
     */
    public function toPath($path)
    {
        return $this->toBase().'/'.$path;
    }

    /**
     * Generate a URL to base with UrlGenerator's prefix.
     *
     * @return string
     */
    public function toBase()
    {
        return $this->app->url($this->path);
    }
}
