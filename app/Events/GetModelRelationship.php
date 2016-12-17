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
 * The `GetModelRelationship` event is called to retrieve Relation object for a
 * model. Listeners should return an Eloquent Relation object.
 */
class GetModelRelationship
{
    /**
     * @var AbstractModel
     */
    public $model;

    /**
     * @var string
     */
    public $relationship;

    /**
     * @param AbstractModel $model
     * @param string $relationship
     */
    public function __construct(AbstractModel $model, $relationship)
    {
        $this->model = $model;
        $this->relationship = $relationship;
    }

    /**
     * @param string $model
     * @param string $relationship
     * @return bool
     */
    public function isRelationship($model, $relationship)
    {
        return $this->model instanceof $model && $this->relationship === $relationship;
    }
}
