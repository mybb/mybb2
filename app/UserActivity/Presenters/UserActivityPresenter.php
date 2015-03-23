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

use MyBB\Core\UserActivity\Database\Models\UserActivity;
use Mybb\Core\UserActivity\Renderers\AbstractRenderer;

class UserActivityPresenter
{
    /**
     * @var UserActivity $activity
     */
    protected $activity;
    /**
     * @var AbstractRenderer $renderer
     */
    protected $renderer;

    /**
     * @param UserActivity     $activity
     * @param AbstractRenderer $renderer
     */
    public function __construct(UserActivity $activity, AbstractRenderer $renderer = null)
    {
        $this->activity = $activity;
        $this->renderer = $renderer;
    }

    /**
     * Render the activity string.
     *
     * @return string
     */
    public function activity()
    {
        if ($this->renderer !== null) {
            return $this->renderer->render($this->activity);
        }

        // TODO: Baseline activity string...
        return '';
    }

    /**
     * Magic __call method, delegating all other methods to the UserActivity class.
     *
     * @param string      $name      The name of the method to call.
     * @param array|mixed $arguments The method arguments.
     *
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func([$this->activity, $name], $arguments);
    }
}
