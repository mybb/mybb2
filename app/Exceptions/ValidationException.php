<?php

/**
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */


namespace MyBB\Core\Exceptions;

use Exception;

class ValidationException extends Exception
{
    protected $attributes;
    protected $relationships;

    public function __construct(array $attributes, array $relationships = [])
    {
        $this->attributes = $attributes;
        $this->relationships = $relationships;

        $messages = [implode("\n", $attributes), implode("\n", $relationships)];

        parent::__construct(implode("\n", $messages));
    }

    public function getAttributes()
    {
        return $this->attributes;
    }

    public function getRelationships()
    {
        return $this->relationships;
    }
}
