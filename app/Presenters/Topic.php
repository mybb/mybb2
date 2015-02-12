<?php
/**
 * Thread presenter class.
 *
 * @version 2.0.0
 * @author MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Presenters;

use McCool\LaravelAutoPresenter\BasePresenter;
use MyBB\Core\Database\Models\Topic as TopicModel;

class Topic extends BasePresenter
{
    /** @var TopicModel $wrappedObject */

    /**
     * @param TopicModel $resource The thread being wrapped by this presenter.
     */
    public function __construct(TopicModel $resource)
    {
        $this->wrappedObject = $resource;
    }
}
