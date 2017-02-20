<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Widgets\Twig;

use MyBB\Core\Widgets\Registry;

class Widget extends \Twig_Extension
{
    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @param Registry $registry The widget registry.
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName() : string
    {
        return 'MyBB_Widgets_Twig_Extensions_Widget';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('render_widget', [$this->registry, 'renderWidget'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction(
                'widget_position',
                [$this->registry, 'renderWidgetsForPosition'],
                ['is_safe' => ['html']]
            ),
        ];
    }
}
