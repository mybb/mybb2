<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Providers;

use Illuminate\Bus\Dispatcher;
use Illuminate\Support\ServiceProvider;

class BusServiceProvider extends ServiceProvider
{

	/**
	 * Bootstrap any application services.
	 *
	 * @param  \Illuminate\Bus\Dispatcher $dispatcher
	 *
	 * @return void
	 */
	public function boot(Dispatcher $dispatcher)
	{
		$dispatcher->mapUsing(function ($command) {
		
			return Dispatcher::simpleMapping(
				$command,
				'MyBB\Core\Commands',
				'MyBB\Core\Handlers\Commands'
			);
		});
	}

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}
}
