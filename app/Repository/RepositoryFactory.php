<?php

namespace MyBB\Core\Repository;

use Illuminate\Contracts\Foundation\Application;
use MyBB\Core\Database\Repositories\RepositoryInterface;

class RepositoryFactory
{
    /**
     * @var RepositoryRegistry
     */
    protected $registry;

    /**
     * @var Application
     */
    protected $application;

    /**
     * @param RepositoryRegistry $registry
     * @param Application $application
     */
    public function __construct(RepositoryRegistry $registry, Application $application)
    {
        $this->registry = $registry;
        $this->application = $application;
    }

    /**
     * @param string $key
     *
     * @return RepositoryInterface
     */
    public function build($key)
    {
        $class = $this->registry->get($key);
        return $this->application->make($class);
    }
}
