<?php
/**
 * Renderer Factory.
 *
 * Given an activity type name, returns the corresponding renderer.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2015, MyBB Group
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\UserActivity;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Translation\Translator;
use MyBB\Core\UserActivity\Database\Models\UserActivity;
use Mybb\Core\UserActivity\Renderers\AbstractRenderer;
use MyBB\Core\UserActivity\Renderers\LikeRenderer;
use MyBB\Core\UserActivity\Renderers\PostRenderer;
use MyBB\Core\UserActivity\Renderers\RegistrationRenderer;
use MyBB\Core\UserActivity\Renderers\TopicRenderer;

class RendererFactory
{
	/**
	 * @var Translator $lang
	 */
	protected $lang;
	/**
	 * @var Application $app
	 */
	protected $app;
	/**
	 * Activity types and associated renderers.
	 *
	 * @var AbstractRenderer[]
	 */
	protected $types = [];

	/**
	 * @param Translator  $lang
	 * @param Application $app
	 */
	public function __construct(Translator $lang, Application $app)
	{
		$this->lang = $lang;
		$this->app = $app;
	}

	/**
	 * Build the renderer for a given activity entry.
	 *
	 * @param UserActivity $activity The activity to render.
	 *
	 * @return AbstractRenderer|null The renderer, or null if no renderer is found.
	 */
	public function build(UserActivity $activity)
	{
		$renderer = null;

		switch ($activity->activity_type) {
			case PostRenderer::ACTIVITY_NAME:
				$renderer = '\MyBB\Core\UserActivity\Renderers\PostRenderer';
				break;
			case TopicRenderer::ACTIVITY_NAME:
				$renderer = '\MyBB\Core\UserActivity\Renderers\TopicRenderer';
				break;
			case LikeRenderer::ACTIVITY_NAME:
				$renderer = '\MyBB\Core\UserActivity\Renderers\LikeRenderer';
				break;
			case RegistrationRenderer::ACTIVITY_NAME:
				$renderer = '\MyBB\Core\UserActivity\Renderers\RegistrationRenderer';
				break;
			default:
				if (isset($this->types[$activity->activity_type])) {
					$renderer = $this->types[$activity->activity_type];
				}
				break;
		}

		return $this->app->make($renderer);
	}

	/**
	 * Add a renderer instance.
	 *
	 * @param string $activityTypeName The name of the activity type the renderer applies to.
	 * @param string $renderer         The renderer to add.
	 */
	public function addRenderer($activityTypeName = '', $renderer = '')
	{
		if (class_exists((string) $renderer)) {
			$this->types[(string) $activityTypeName] = (string) $renderer;
		}
	}
}
