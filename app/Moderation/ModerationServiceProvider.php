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
use MyBB\Core\Database\Repositories\Eloquent\ModerationLogRepository;
use MyBB\Core\Database\Repositories\Eloquent\ModerationLogSubjectRepository;
use MyBB\Core\Database\Repositories\ModerationLogRepositoryInterface;
use MyBB\Core\Database\Repositories\ModerationLogSubjectRepositoryInterface;
use MyBB\Core\Moderation\Logger\DatabaseLogger;
use MyBB\Core\Moderation\Logger\ModerationLoggerInterface;
use MyBB\Core\Moderation\Moderations;

class ModerationServiceProvider extends ServiceProvider
{
	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton(ModerationRegistry::class, function (Application $app) {
			return new ModerationRegistry([
				$app->make(Moderations\Approve::class),
				$app->make(Moderations\MovePost::class),
				$app->make(Moderations\MergePosts::class),
				$app->make(Moderations\DeletePost::class),
				$app->make(Moderations\DeleteTopic::class),
				$app->make(Moderations\Close::class),
				$app->make(Moderations\MoveTopic::class)
			]);
		});

		$this->app->bind(
			ModerationLogRepositoryInterface::class,
			ModerationLogRepository::class
		);

		$this->app->bind(
			ModerationLogSubjectRepositoryInterface::class,
			ModerationLogSubjectRepository::class
		);

		$this->app->bind(
			ModerationLoggerInterface::class,
			DatabaseLogger::class
		);
	}
}
