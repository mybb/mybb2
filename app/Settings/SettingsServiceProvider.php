<?php

/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Settings;

use MyBB\Core\Kernel\AbstractServiceProvider;

class SettingsServiceProvider extends AbstractServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->app->singleton('MyBB\Settings\SettingsRepositoryInterface', function () {
            return new MemoryCacheSettingsRepository(
                new DatabaseSettingsRepository(
                    $this->app->make('Illuminate\Database\ConnectionInterface')
                )
            );
        });

        $this->app->alias('MyBB\Settings\SettingsRepositoryInterface', 'mybb.settings');
    }
}
