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
    public $model;

    /**
     * @var array
     */
    public $attributes;

    /**
     * @param AbstractModel $model
     * @param array $attributes
     */
    public function __construct(AbstractModel $model, array &$attributes)
    {
        $this->model = $model;
        $this->attributes = &$attributes;
    }

    /**
     * @param string $model
     * @return bool
     */
    public function isModel($model)
    {
        return $this->model instanceof $model;
    }
}
