<?php
/**
 * Forum presenter class.
 *
 * @author    MyBB Group
 * @version   2.0.0
 * @package   mybb/core
 * @license   http://www.mybb.com/licenses/bsd3 BSD-3
 */

namespace MyBB\Core\Presenters;

use McCool\LaravelAutoPresenter\BasePresenter;
use MyBB\Core\Database\Models\ConversationMessage as ConversationMessageModel;

class ConversationMessagePresenter extends BasePresenter
{
    /** @var ConversationMessageModel $wrappedObject */

    /**
     * @param ConversationMessageModel $resource The conversation being wrapped by this presenter.
     */
    public function __construct(ConversationMessageModel $resource)
    {
        $this->wrappedObject = $resource;
    }

    /**
     * @return User
     */
    public function author()
    {
        if ($this->wrappedObject->author instanceof User) {
            return $this->wrappedObject->author;
        }

        return app()->make('MyBB\Core\Presenters\UserPresenter', [$this->wrappedObject->author]);
    }
}
