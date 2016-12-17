<?php

/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Events;

use MyBB\Core\Extension\Extension;

class ExtensionWasDisabled
{
    /**
     * @var string
     */
    protected $extension;

    /**
     * @param Extension $extension
     */
    public function __construct(Extension $extension)
    {
        $this->extension = $extension;
    }
}
