<?php
/**
 * Post presenter class.
 *
 * @version 1.0.0
 * @author MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Presenters;

use McCool\LaravelAutoPresenter\BasePresenter;

class Post extends BasePresenter
{
    /** @var \MyBB\Core\Database\Models\Post $wrappedObject */

    /**
     * @param \MyBB\Core\Database\Models\Post $resource The post being wrapped by this presenter.
     */
    public function __construct(\MyBB\Core\Database\Models\Post $resource)
    {
        $this->wrappedObject = $resource;
    }
}
