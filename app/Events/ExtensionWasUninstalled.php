<?php

/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Events;

class ExtensionWasUninstalled
{
    /**
     * @var string
     */
    protected $extension;

    /**
     * @param string $extension
     */
    public function __construct(string $extension)
    {
        $this->extension = $extension;
    }
}
