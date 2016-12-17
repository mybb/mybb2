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
    public $modelClass;

    /**
     * @var array
     */
    public $dates;

    /**
     * @param AbstractModel $modelClass
     * @param array $dates
     */
    public function __construct(AbstractModel $modelClass, array &$dates)
    {
        $this->model = $modelClass;
        $this->dates = &$dates;
    }

    /**
     * @param string $modelClass
     * @return bool
     */
    public function isModel(string $modelClass)
    {
        return $this->modelClass instanceof $modelClass;
    }
}
