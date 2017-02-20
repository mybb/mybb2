<?php
/**
 * Current path helper for Twig.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @copyright Copyright (c) 2015, MyBB Group
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 * @link      http://www.mybb.com
 */

namespace MyBB\Core\Twig\Extensions;

use Illuminate\Http\Request;
use Twig_Extension;
use Twig_SimpleFunction;

class CurrentPath extends Twig_Extension
{
    /**
     * @var string
     */
    protected $currentPath;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'MyBB_Twig_Extensions_CurrentPath';
    }

    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('current_path', [$this, 'currentPath']),
        ];
    }

    /**
     * @return string
     */
    public function currentPath() : string
    {
        if ($this->currentPath == null) {
            $path = $this->request->path();

            if ($path == '/') {
                $path = '';
            }

            $this->currentPath = $path;
        }

        return $this->currentPath;
    }
}
