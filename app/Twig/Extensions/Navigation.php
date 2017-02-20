<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Twig\Extensions;

class Navigation extends \Twig_Extension
{
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName() : string
    {
        return 'MyBB_Twig_Extensions_Navigation';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('active_tab', function ($context, $target) {
                return array_key_exists('active', $context) && $context['active'] == $target ? ' class=active ' : '';
            }, ['needs_context' => true]),
        ];
    }
}
