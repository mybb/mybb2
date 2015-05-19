<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Http\Middleware;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\View\Factory;
use MyBB\Settings\Store;

class SetupLanguage extends AbstractBootstrapMiddleware
{
	/**
	 * @var Factory
	 */
	private $viewFactory;

	/**
	 * @var Store
	 */
	private $settings;

	/**
	 * @var Application
	 */
	private $app;

	/**
	 * @param Factory     $viewFactory
	 * @param Store       $settings
	 * @param Application $app
	 */
	public function __construct(Factory $viewFactory, Store $settings, Application $app)
	{
		$this->viewFactory = $viewFactory;
		$this->settings = $settings;
		$this->app = $app;
	}

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \Closure                 $next
	 *
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		$this->app->setLocale($this->settings->get('user.language', 'en'));

		$langDir = [
			'left' => 'left',
			'right' => 'right'
		];
		if (trans('general.direction') == 'rtl') {
			$langDir['left'] = 'right';
			$langDir['right'] = 'left';
		}

		$this->viewFactory->share('langDir', $langDir);

		return $next($request);
	}
}
