<?php

/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Events;

use MyBB\Core\Database\AbstractModel;

class ConfigureModelDefaultAttributes
{
    /**
     * @var AbstractModel
     */
    public $modelClass;

    /**
     * @var array
     */
    public $attributes;

    /**
     * @param AbstractModel $modelClass
     * @param array $attributes
     */
    public function __construct(AbstractModel $modelClass, array &$attributes)
    {
        $this->modelClass = $modelClass;
        $this->attributes = &$attributes;
    }

    /**
     * @param string $modelClass
     * @return bool
     */
    public function isModel($modelClass)
    {
        return $this->modelClass instanceof $modelClass;
    }
}
