<?php
/**
 * Renderer Factory.
 *
 * Given an activity type name, returns the corresponding renderer.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2014, MyBB Group
 * @license   http://www.mybb.com/about/license GNU LESSER GENERAL PUBLIC LICENSE
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\UserActivity;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Manager;
use Illuminate\Support\Str;
use Illuminate\Translation\Translator;
use MyBB\Core\UserActivity\Database\Models\UserActivity;
use Mybb\Core\UserActivity\Renderers\AbstractRenderer;
use MyBB\Core\UserActivity\Renderers\PostRenderer;

class RendererFactory
{
    /**
     * @var Translator $lang
     */
    protected $lang;
    /**
     * Activity types and associated renderers.
     *
     * @var AbstractRenderer[]
     */
    protected $types = [];

    /**
     * @param Translator $lang
     */
    public function __construct(Translator $lang)
    {
        $this->lang = $lang;
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

        switch ($activity->getActivityType()) {
            case PostRenderer::ACTIVITY_NAME:
                $renderer = new PostRenderer($this->lang);
                break;
            default:
                if (isset($this->types[$activity->getActivityType()])) {
                    $renderer = $this->types[$activity->getActivityType()];
                }
                break;
        }

        return $renderer;
    }

    /**
     * Add a renderer instance.
     *
     * @param AbstractRenderer $renderer The renderer to add.
     */
    public function addRenderer(AbstractRenderer $renderer)
    {
        $this->types[$renderer->getActivityTypeName()] = $renderer;
    }
}
