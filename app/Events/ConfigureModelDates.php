<?php

/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Events;

use MyBB\Core\Database\AbstractModel;

/**
 * The `ConfigureModelDates` event is called to retrieve a list of fields for a model
 * that should be converted into date objects.
 */
class ConfigureModelDates
{
    /**
     * @var AbstractModel
     */
    public $model;

    /**
     * @var array
     */
    public $dates;

    /**
     * @param AbstractModel $model
     * @param array $dates
     */
    public function __construct(AbstractModel $model, array &$dates)
    {
        $this->model = $model;
        $this->dates = &$dates;
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
