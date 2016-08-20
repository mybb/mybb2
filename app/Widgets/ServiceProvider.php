<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Widgets;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $reg = new Registry($this->app);
        $reg->registerWidgets([
            Widgets\RenderViewWidget::getName() => Widgets\RenderViewWidget::class,
            Widgets\UsersOnlineWidget::getName() => Widgets\UsersOnlineWidget::class,
            Widgets\ForumListWidget::getName() => Widgets\ForumListWidget::class,
        ]);

        $this->app->instance(Registry::class, $reg);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            Registry::class,
        ];
    }
}
