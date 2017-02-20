<?php
/**
 * List all forums extension for Twig.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2015, MyBB Group
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\Twig\Extensions;

use Twig_Extension;
use Twig_SimpleFunction;

class ListAllForums extends Twig_Extension
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'MyBB_Twig_Extensions_ListAllForums';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('list_all_forums', [$this, 'renderForums'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param $forums
     * @param string $template
     * @param null $options
     * @param int $level
     * @return string
     */
    public function renderForums(
        $forums,
        string $template = 'admin.forums.forum-item',
        $options = null,
        int $level = 1
       ) : string {
        $result = '';
        foreach ($forums as $forum) {
            $result .= view($template, compact('forum', 'level', 'options'))->render();
            if ($forum->children->count() > 0) {
                $result .= $this->renderForums($forum->children, $template, $options, $level + 1);
            }
        }
        return $result;
    }
}
