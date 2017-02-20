<?php
/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Warnings;

use Illuminate\Contracts\Foundation\Application;
use MyBB\Core\Exceptions\WarningsContentInvalidClassException;

class WarningsManager
{

    /**
     * @var Application
     */
    private $app;

    /**
     * WarningsManager constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param string $contentName
     * @return WarnableContentInterface
     * @throws WarningsContentInvalidClassException
     */
    public function getWarningContentClass(string $contentName)
    {

        $contentClass = 'MyBB\\Core\\Warnings\\Warnings' . ucfirst($contentName) . 's';

        if (!class_exists($contentClass)) {
            throw new WarningsContentInvalidClassException($contentClass);
        }

        $content = $this->app->make($contentClass);

        if (!$content || !($content instanceof WarnableContentInterface)) {
            throw new WarningsContentInvalidClassException($contentClass);
        }

        return $content;
    }
}
