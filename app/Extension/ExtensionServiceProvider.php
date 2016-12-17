<?php

/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Extension;

use MyBB\Core\Kernel\AbstractServiceProvider;

class ExtensionServiceProvider extends AbstractServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->app->bind('mybb.extensions', \MyBB\Core\Extension\ExtensionManager::class);

        $bootstrappers = $this->app->make('mybb.extensions')->getEnabledBootstrappers();

        foreach ($bootstrappers as $file) {
            $bootstrapper = require $file;

            $this->app->call($bootstrapper);
        }
    }
}
