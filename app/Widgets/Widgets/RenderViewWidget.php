<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Widgets\Widgets;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use MyBB\Core\Widgets\WidgetInterface;

/**
 * A simple widget to render a given view. Can be useful to display arbitrary HTML content.
 */
class RenderViewWidget implements WidgetInterface
{
    /**
     * @var Factory $viewFactory
     */
    protected $viewFactory;

    public function __construct(Factory $viewFactory)
    {
        $this->viewFactory = $viewFactory;
    }

    /**
     * Get the name of the widget.
     *
     * @return string The name of the widget.
     */
    public static function getName(): string
    {
        return 'render_view';
    }

    /**
     * Render the widget.
     *
     * @param array $parameters An optional array of parameters passed to the widget.
     *
     * @return Renderable The renderable content to render within the view.
     */
    public function render(array $parameters = null): Renderable
    {
        // At least the view name should be passed as a parameter.
        if ($parameters != null && isset($parameters['name'])) {
            $viewName = $parameters['name'];
            $viewParameters = [];
            if (isset($parameters['parameters'])) {
                $viewParameters = $parameters['parameters'];
                if (!is_array($viewParameters)) {
                    $viewParameters = [$viewParameters];
                }
            }

            return $this->viewFactory->make($viewName, $viewParameters);
        }

        return null;
    }
}
