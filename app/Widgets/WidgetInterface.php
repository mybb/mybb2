<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Widgets;

use Illuminate\Contracts\Support\Renderable;

/**
 * Base widget, all widgets should implement this interface.
 */
interface WidgetInterface
{
    /**
     * Get the name of the widget.
     *
     * @return string The name of the widget.
     */
    public static function getName(): string;

    /**
     * Render the widget.
     *
     * @param array $parameters An optional array of parameters passed to the widget.
     *
     * @return Renderable The renderable content to render within the view.
     */
    public function render(array $parameters = null): Renderable;
}
