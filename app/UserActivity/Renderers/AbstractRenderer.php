<?php
/**
 * User activity renderer.
 *
 * Given a user activity entry, a renderer should return the string used to display the entry.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2014, MyBB Group
 * @license   http://www.mybb.com/about/license GNU LESSER GENERAL PUBLIC LICENSE
 * @link      http://www.mybb.com
 */

namespace Mybb\Core\UserActivity\Renderers;

use Illuminate\Translation\Translator;
use MyBB\Core\UserActivity\Database\Models\UserActivity;

abstract class AbstractRenderer
{
    /**
     * @var Translator $lang
     */
    protected $lang;

    /**
     * @param Translator $lang
     */
    public function __construct(Translator $lang)
    {
        $this->lang = $lang;
    }

    /**
     * Get the full activity type name.
     *
     * EG: "MyBB\Core\Database\Models\Post".
     *
     * @return string
     */
    abstract public function getActivityTypeName();

    /**
     * Render a given activity entry into a readable string.
     *
     * @param UserActivity $activity The activity to render.
     *
     * @return string The activity string. This string is not escaped on output, so should be properly cleaned before
     *                return.
     */
    abstract public function render(UserActivity $activity);
}
