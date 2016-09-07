<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Moderation;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class ModerationServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('MyBB\Core\Moderation\ModerationRegistry', function (Application $app) {
            return new ModerationRegistry([
                $app->make('MyBB\Core\Moderation\Moderations\Approve'),
                $app->make('MyBB\Core\Moderation\Moderations\MovePost'),
                $app->make('MyBB\Core\Moderation\Moderations\MergePosts'),
                $app->make('MyBB\Core\Moderation\Moderations\DeletePost'),
                $app->make('MyBB\Core\Moderation\Moderations\DeleteTopic'),
                $app->make('MyBB\Core\Moderation\Moderations\Close'),
                $app->make(\MyBB\Core\Moderation\Moderations\Stick::class),
                $app->make('MyBB\Core\Moderation\Moderations\MoveTopic'),
            ]);
        });

        $this->app->bind(
            'MyBB\Core\Database\Repositories\ModerationLogRepositoryInterface',
            'MyBB\Core\Database\Repositories\Eloquent\ModerationLogRepository'
        );

        $this->app->bind(
            'MyBB\Core\Database\Repositories\ModerationLogSubjectRepositoryInterface',
            'MyBB\Core\Database\Repositories\Eloquent\ModerationLogSubjectRepository'
        );

        $this->app->bind(
            'MyBB\Core\Moderation\Logger\ModerationLoggerInterface',
            'MyBB\Core\Moderation\Logger\DatabaseLogger'
        );
    }
}
