<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Widgets;

use Illuminate\Contracts\Container\Container;

/**
 * The widget registry holds information relating to widgets and widget positions.
 */
class Registry
{
    /**
     * @var Container $app
     */
    protected $app;

    /**
     * A list of widgets, mapping widget names to class implementation names.
     *
     * @var array
     */
    protected $widgets = [];

    /**
     * A list of widget positions, mapping positions to a list of associated widget names.
     *
     * @var array
     */
    protected $widgetPositions = [];

    /**
     * Registry constructor.
     *
     * @param Container $app The IOC container used to initialise widgets.
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * Register a widget with the registry.
     *
     * @param string $name The name of the widget to register.
     * @param string $className The class name for the widget.
     */
    public function registerWidget(string $name, string $className)
    {
        if (empty($name)) {
            throw new \InvalidArgumentException('Widget name is required.');
        }

        if (!class_exists($className)) {
            throw new \InvalidArgumentException("Class ${className} does not exist.");
        }

        $this->widgets[$name] = $className;
    }

    /**
     * Get the widget with the given name from the registry.
     *
     * @param string $name The name of the widget to retrieve.
     *
     * @return WidgetInterface The widget instance.
     */
    public function getWidget(string $name) : WidgetInterface
    {
        if (!isset($this->widgets[$name])) {
            throw new \InvalidArgumentException("Widget '${name}' is not registered.");
        }

        return $this->app->make($this->widgets[$name]);
    }

    /**
     * Add a widget for the given position.
     *
     * @param string $position The name of the position.
     * @param string $widgetName The name of the widget to add.
     * @param array $parameters An array of parameters to be passed to the widget's `render` method.
     */
    public function addWidgetForPosition(string $position, string $widgetName, array $parameters = null)
    {
        if (empty($position)) {
            throw new \InvalidArgumentException('Widget position is required.');
        }

        if (empty($widgetName)) {
            throw new \InvalidArgumentException('Widget name is required.');
        }

        if (!isset($this->widgetPositions[$position])) {
            $this->widgetPositions[$position] = [['name' => $widgetName, 'parameters' => $parameters]];
        } else {
            $this->widgetPositions[$position][] = ['name' => $widgetName, 'parameters' => $parameters];
        }
    }

    /**
     * Render all of the widgets for the given position.
     *
     * @param string $position The position to render the widgets for.
     *
     * @return \Generator
     */
    public function renderWidgetsForPosition(string $position): \Generator
    {
        if (isset($this->widgetPositions[$position])) {
            foreach ($this->widgetPositions[$position] as $widgetDetails) {
                if (isset($this->widgets[$widgetDetails['name']])) {
                    $widget = $this->widgets[$widgetDetails['name']];
                    /** @var WidgetInterface $widgetInstance */
                    $widgetInstance = $this->app->make($widget);
                    yield $widgetInstance->render($widgetDetails['parameters']);
                }
            }
        }
    }

    /**
     * Render the given widget.
     *
     * @param string $widget The name of the widget to render.
     * @param array|null $parameters A list of parameters to pass to the widget being rendered.
     *
     * @return \Illuminate\Contracts\Support\Renderable|null
     */
    public function renderWidget(string $widget, array $parameters = null)
    {
        if (isset($this->widgets[$widget])) {
            /** @var WidgetInterface $widgetInstance */
            $widgetInstance = $this->app->make($this->widgets[$widget]);
            return $widgetInstance->render($parameters);
        }

        return null;
    }
}
