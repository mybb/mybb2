<?php
/**
 * Forum presenter class.
 *
 * @version 2.0.0
 * @author MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Presenters;

use McCool\LaravelAutoPresenter\BasePresenter;
use MyBB\Core\Database\Models\Forum as ForumModel;

class Forum extends BasePresenter
{
    /** @var ForumModel $wrappedObject */

    /**
     * @param ForumModel $resource The forum being wrapped by this presenter.
     */
    public function __construct(ForumModel $resource)
    {
        $this->wrappedObject = $resource;
    }
}
