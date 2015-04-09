<?php
/**
 * User activity presenter.
 *
 * Manually instantiated presenter, used to present an activity to the view, using a given renderer.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2014, MyBB Group
 * @license   http://www.mybb.com/about/license GNU LESSER GENERAL PUBLIC LICENSE
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\UserActivity\Presenters;

use McCool\LaravelAutoPresenter\BasePresenter;
use MyBB\Core\UserActivity\Database\Models\UserActivity;
use MyBB\Core\UserActivity\RendererFactory;
use Mybb\Core\UserActivity\Renderers\AbstractRenderer;

class UserActivityPresenter extends BasePresenter
{
    /**
     * @var UserActivity $wrappedObject
     */

    /**
     * @var RendererFactory $renderer
     */
    protected $rendererFactory;

    /**
     * @param UserActivity    $resource
     * @param RendererFactory $rendererFactory
     *
     * @internal param AbstractRenderer $renderer
     */
    public function __construct(UserActivity $resource, RendererFactory $rendererFactory)
    {
        parent::__construct($resource);
        $this->rendererFactory = $rendererFactory;
    }

    /**
     * Render the activity string.
     *
     * @return string
     */
    public function activityString()
    {
        $renderer = $this->rendererFactory->build($this->getWrappedObject());

        if ($renderer !== null) {
            return $renderer->render($this->getWrappedObject());
        }

        // TODO: Baseline activity string...
        return '';
    }
}
