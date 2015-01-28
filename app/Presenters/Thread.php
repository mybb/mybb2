<?php
/**
 * Thread presenter class.
 *
 * @version 1.0.0
 * @author MyBB Group
 * @license LGPL v3
 */

namespace MyBB\Core\Presenters;

use McCool\LaravelAutoPresenter\BasePresenter;

class Thread extends BasePresenter
{
    /** @var \MyBB\Core\Database\Models\Thread $wrappedObject */

    /**
     * @param \MyBB\Core\Database\Models\Thread $thread The thread being wrapped by this presenter.
     */
    public function __construct(\MyBB\Core\Database\Models\Thread $thread)
    {
        $this->wrappedObject = $thread;
    }
}
